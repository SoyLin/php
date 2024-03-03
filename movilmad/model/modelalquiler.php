<?php
include "./db/datadb.php";

function showproducts() {
    datadb();
    $conexion = conectar();
    $sql = "SELECT matricula, marca, modelo FROM rvehiculos WHERE disponible='S'";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    $filas = $consulta->fetchAll(PDO::FETCH_ASSOC);
    echo " <h1>Servicio de ALQUILER DE E-CARS</h1> ";
    echo "<form action='' method='post'>";
    echo "<select name='vehiculos'>";
    foreach ($filas as $fila) {
        pintarproductos($fila['matricula'], $fila['marca'], $fila['modelo']);
    }
    echo "</select>";
    $conexion = NULL;
}

function pintarproductos($matricula, $marca, $modelo) {
    echo "<option value='$matricula'>$matricula $marca $modelo </option>";
}
function checklog(){
    session_start();
    if (!isset($_SESSION['nombre'])) {
        header('Location: nologin.html');
        exit;
     }else{
        datoscliente();
     }
}
function datoscliente(){
    echo "Bienvenido/a: ". $_SESSION['nombre'];
    echo "<br>";
    echo "Identificador Cliente: ". $_SESSION['idcliente'];
}
///CARRITO/////////////
function addcarrito() {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }
    if (count($_SESSION['carrito'])>=3) {
        echo "El maximo de vehiculos para alquilar es 3";
    }else {
        $matricula=$_POST['vehiculos'];
    $coche=consultarNombre($matricula);
        $productinfo = array(
            'matricula' => $matricula,
            'Coche' => $coche
        );
        $_SESSION['carrito'][$matricula] = $productinfo;
    
    echo "<ul>";
foreach ($_SESSION['carrito'] as $key ) {
    echo "<li>".$key['Coche']."</li>";
}
    echo "</ul>";
    echo "Producto añadido satisfactoriamente";
    }
   var_dump($_SESSION['carrito']);
}
function consultarNombre($matricula){
    datadb();
    $conexion = conectar();
    $sql = "SELECT marca, modelo FROM rvehiculos WHERE matricula=:matricula";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':matricula', $matricula);
    $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    $coche= $datos[0]['marca']." ". $datos[0]['modelo'];
    $conexion = NULL;
    return $coche;
}
function borrarcarrito(){
    $_SESSION['carrito']=array();
}
function consultarAlquileres(){
    datadb();
    $conexion = conectar();
    $sql = "SELECT COUNT(matricula) AS total FROM ralquileres WHERE idcliente=:idcliente AND fecha_devolucion IS NULL";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':idcliente', $_SESSION['idcliente']);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    $cantalquiler = $resultado['total'];
    $conexion = NULL;
    return $cantalquiler;
}

function estadoVehiculo($matricula){
    datadb();   
echo $matricula;
echo "HOLA";
     try {
        $conexion = conectar();
        $sql = "UPDATE rvehiculos 
        SET disponible ='N'
        WHERE matricula = :matricula;        
        ";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':matricula', $matricula);

        $consulta->execute();
        $conexion = NULL;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function comprobarAlquiler(){
    $vehiculosalquilados=consultarAlquileres();
    $cantidadAlquilar= count($_SESSION['carrito']);
    $aux = intval($vehiculosalquilados) + intval($cantidadAlquilar);
    echo $vehiculosalquilados;
    echo $cantidadAlquilar;
    echo $aux;
    if ($aux>3) {
        echo "<script>alert('La cantidad maxima en alquiler es de 3 vehiculos, devuelva vehiculos para poder alquilar mas')</script>";

    }else {
        foreach ($_SESSION['carrito'] as $key) {
            insertarAlquiler($key['matricula']);
            $_SESSION['carrito']=array();
        }
    }
}
function insertarAlquiler($matricula){
    datadb();
    $conexion = conectar();
    $sql = "INSERT INTO ralquileres (idcliente,matricula,fecha_alquiler)
    VALUES (:idcliente,:matricula,NOW())";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':idcliente', $_SESSION['idcliente']);
    $consulta->bindParam(':matricula', $matricula);
    $consulta->execute();
echo 'Alquiler realizado con exito';
    $conexion = NULL;
}
function logoff(){
    session_unset();
    session_destroy();
    $cookies = $_COOKIE;
    foreach ($cookies as $cookie_name => $cookie_value) {
        setcookie($cookie_name, '', time() - 3600, '/');
    }
    header('Location: nologin.html');
    exit;
    }
?>