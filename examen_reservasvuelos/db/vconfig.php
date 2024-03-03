<?php
 
   function conexion(){
      define('DB_SERVER', 'localhost');
      define('DB_USERNAME', 'root');
      define('DB_PASSWORD', 'rootroot');
      define('DB_DATABASE', 'reservas');
      
      $servername = DB_SERVER;
      $dbname = DB_DATABASE;
      $username = DB_USERNAME;
      $password = DB_PASSWORD;

      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
   } 


?>