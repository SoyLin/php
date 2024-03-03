<?php
include_once './model/modeldevolver.php';
checklog();
showproducts();
echo "<br>";
echo "<br>";

if (isset($_POST['devolver'])) {
    $matricula=$_POST['vehiculos'];
    finalquiler($matricula);
    estadoVehiculo($matricula);
    pago();
}

?>