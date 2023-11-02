<?php
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
      header('Access-Control-Allow-Headers: token, Content-Type');
      header('Access-Control-Max-Age: 1728000');
      header('Content-Length: 0');
      header('Content-Type: text/plain');
      die();
  }

  header('Access-Control-Allow-Origin: *');
   

   header('Content-Type: application/json');
   require_once __DIR__ . '/database/database.php';
   
   $request = rtrim($_SERVER['REQUEST_URI'], '/');
   
   $request_parts = explode('/', $request);
  
   $db = new DBConnection();

if($_SERVER["REQUEST_METHOD"] === 'GET'){
   
   if($request_parts[1] == 'users'){

      // Get all users (/users)
      if(count($request_parts) == 2){
         $users = $db->getAllUsers();
         echo json_encode($users);   
      }

      // Get user by id (/users/:id)
      else if(count($request_parts) == 3){
         $user_id = $request_parts[2];
         $user = $db->findById($user_id);
         //echo json_encode($user); 
         echo json_encode($user);
      }

      // Get user by name (/users/name/:name)
      else if($request_parts[2] == 'name' && count($request_parts) == 4){
         $user_name = $request_parts[3]; 
         $user = $db->findByName($user_name);
         http_response_code(201);
         echo json_encode([$user]);
      }

   }
}else if($_SERVER["REQUEST_METHOD"] === 'POST'){
   //echo json_encode($request_parts);
   
   if($request_parts[1] == 'users'){
      $request_body = json_decode(file_get_contents('php://input'), true);
      
      //create user (/user)
      error_log(json_encode($request_parts), 0); 
      if(count($request_parts) == 2){
         //$users = $db->getAllUsers();
         $name = $request_body['name'];
         $password = $request_body['password'];
         $highscore = 0;
         if(array_key_exists('highscore', $request_body)){
            $highscore = $request_body['highscore'];
         }
        
         $newUser = $db->addUser($name, $password, $highscore);
         if($newUser){
            http_response_code(201);
                           
            echo json_encode([$newUser]);
         }else{
            http_response_code(400);
            echo json_encode(['message' => "Bad request"]);
         }

      }

      //login by session
      else if($request_parts[2] == 'session' && count($request_parts) == 3){
         if(array_key_exists('session_id', $request_body)){
            $session_id = $request_body['session_id'];
            $user = $db->findBySessionId($session_id);
            if($user){
               http_response_code(201);
               echo json_encode([$user]);
            }else{
               http_response_code(404);
               echo json_encode(['message' => "Invalid session_id"]);
            }
            
         }else{
            http_response_code(404);
            echo json_encode(['message' => "Invalid session_id"]);
         }
         
         //echo json_encode("create new session"); 
      }else if($request_parts[2] == 'login' && count($request_parts) == 3){
        // echo json_encode($request_body);
         $session_id = null;
         $name = null;
         $password = null
         ;
         if(array_key_exists('session_id',$request_body)){
            $session_id = $request_body['session_id'];
         }
         if(array_key_exists('name',$request_body)){
            $name = $request_body['name'];
         }
         if(array_key_exists('password',$request_body)){
            $password = $request_body['password'];
         }
         
         $user = $db->userLogin($session_id, $name, $password);
         echo json_encode([$user]);
         
      }

   }
}else if($_SERVER["REQUEST_METHOD"] === 'PATCH'){
   if($request_parts[1] == 'users'){
      $request_body = json_decode(file_get_contents('php://input'), true);
      
     
      //update highscore
      if(count($request_parts) == 3){
         $user_id = $request_parts[2];
         $password = null;
         $highscore = null;

         if(array_key_exists('password', $request_body)){
            $password = $request_body['password'];
         }
         if(array_key_exists('highscore', $request_body)){
            $highscore = $request_body['highscore'];
         }

         $user = $db->updateHighscore($user_id, $password, $highscore);
         if($user){
            echo json_encode($user);
         }else{
            http_response_code(404);
            echo json_encode(['message' => 'Incorrect password']);
         }

         
      }
   }
}


   

   
   
   