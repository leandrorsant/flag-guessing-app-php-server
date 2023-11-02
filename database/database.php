<?php
   
   require_once dirname(__FILE__) . '/../vendor/autoload.php';
   require_once dirname(__FILE__) . '/../env.php';
   require_once dirname(__FILE__) . '/../model/user.php';

class DBConnection {
    private $db;
    private $collection;
    function __construct(){
        $this->db = (new MongoDB\Client(
            MONGODB_URL))->flag_guessing_app;
        $this->collection = $this->db->users;
    }

    public function getAllUsers(){
        $users = array();
        $documents = $this->collection->find([]);
        foreach($documents as $d){
            array_push($users, new User(($d->_id->__toString()), $d->name, $d->password, $d->highscore, $d->session_id ?? ""));
        }
        return $users;
    }

    public function findByName($name){
        if($name){
            $d = $this->collection->findOne(['name' => $name]);
            if($d != null){
                $user  =new User(($d->_id->__toString()), $d->name, $d->password, $d->highscore, $d->session_id ?? "");
                return $user;
            }
        }
        return [];
    }

    public function findById($id){
        if($id){
            try{
                $d = $this->collection->findOne(['_id'=> (new \MongoDB\BSON\ObjectID($id)) ]);
                if($d != null){
                    $user  = new User(($d->_id->__toString()), $d->name, $d->password, $d->highscore, $d->session_id ?? "");
                    return $user;
                }
            }catch(Exception $e){

            }
        }
        return [];
    }

    public function findBySessionId($session_id){
        if($session_id){
            try{
                $d = $this->collection->findOne(['session_id'=> $session_id ]);
                if($d != null){
                    $user = new User(($d->_id->__toString()), $d->name, $d->password, $d->highscore, $d->session_id ?? "");
                    return $user;
                }
            }catch(Exception $e){
                return [];
            }
        }
        return [];
    }

    public function addUser($name, $password, $highscore){
        if($this->findByName($name)){
            return null;
        }else{
            try{
                $insertOneResult = $this->collection->insertOne([
                    'name' => $name,
                    'password' => $password,
                    'highscore' => $highscore
                ]);
                
                $user = $this->findByName($name);
                return $user;

            }catch (Exception $e){
                return null;
            }
            return null;
        }
    }

    public function userLogin($session_id, $name, $password){
        $user = $this->findBySessionId($session_id);
        
        if(!$user){
            $user = $this->findByName($name);
            return $user;
            if($user){
                if($user->password == $password){
                    $new_session_id = new \MongoDB\BSON\ObjectId();
                    $new_session_id->__toString();
                    $this->collection->updateOne(['name' => $name], ['$set' => ['session_id' => $new_session_id] ]);
                }else{
                   return null;
                }
            }
        }
        return $user;
    }

    public function updateHighscore($user_id, $password, $highscore){
        $user = $this->findById($user_id);
        if($user){
            if($user->password == $password){
                $this->collection->updateOne(['_id' => new \MongoDB\BSON\ObjectId($user_id)], ['$set' => ['highscore' => $highscore]]);
                return $user;
            }     
        }
        return null;
    }

    public function getConnection(){
        return $this->db;
    }
}   





