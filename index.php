<?php
   header('Content-Type: application/json');
   require_once __DIR__ . '/database/database.php';
   
   $request = $_SERVER['REQUEST_URI'];
   
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
         $user = $db->findByName($user_id);
         //echo json_encode($user); 
         echo json_encode(new MongoDB\BSON\ObjectId());
      }

      // Get user by name (/users/name/:name)
      else if($request_parts[2] == 'name' && count($request_parts) == 4){
         $user_name = $request_parts[3]; 
         $user = $db->findByName($user_name);
         http_response_code(201);
         echo json_encode($user);
      }

   }
}


   

   
   
   