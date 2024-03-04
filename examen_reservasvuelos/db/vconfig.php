<?php
   function conexion(){
      if (!defined('DB_SERVER')) define('DB_SERVER', 'localhost');
      if (!defined('DB_USERNAME')) define('DB_USERNAME', 'root');
      if (!defined('DB_PASSWORD')) define('DB_PASSWORD', 'rootroot');
      if (!defined('DB_DATABASE')) define('DB_DATABASE', 'reservas');
      // define('DB_SERVER', 'localhost');
      // define('DB_USERNAME', 'root');
      // define('DB_PASSWORD', 'rootroot');
      // define('DB_DATABASE', 'reservas');
      try {
         $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
         // $conn = new PDO("mysql:host=localhost;dbname=reservas", "root", "rootroot");
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         return $conn;

      }  catch (PDOException $e) {
         throw new Exception("Error: " . $e->getMessage());
         return null;
      }
   
   }
?>