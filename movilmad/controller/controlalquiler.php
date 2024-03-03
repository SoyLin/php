<?php
include_once './model/modelalquiler.php';
checklog();

showproducts();
echo "<br>";
echo "<br>";

if (isset($_POST['agregar'])) {
    addcarrito();
 }
 if (isset($_POST['vaciar'])) {
    borrarcarrito();
 }
 if (isset($_POST['alquilar'])) {
  comprobarAlquiler();
  foreach ($_SESSION['carrito'] as $key) {
   echo $key['matricula'];
   estadoVehiculo($key['matricula']);
  }
  

 }
 if (isset($_POST['logoff'])) {
  logoff();
 }
?>
