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
        $d = $this->collection->findOne(['name' => $name]);
        if($d != null){
            $user  =new User(($d->_id->__toString()), $d->name, $d->password, $d->highscore, $d->session_id ?? "");
            return $user;
        }
        return [];
    }

    public function findById($id){
        //var_dump(new MongoDB\BSON\ObjectId());
        $d = $this->collection->find(['_id'=> (new \MongoDB\BSON\ObjectID($id)) ]);
        if($d != null){
            $user  =new User(($d->_id->__toString()), $d->name, $d->password, $d->highscore, $d->session_id ?? "");
            return $user;
        }
        return [];
    }

    public function getConnection(){
        return $this->db;
    }
}   





