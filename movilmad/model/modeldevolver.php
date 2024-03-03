<?php
include "./db/datadb.php";
include './api/apiRedsys.php';
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
function showproducts() {
    datadb();
    $conexion = conectar();
    $sql = "SELECT rvehiculos.marca, rvehiculos.modelo, rvehiculos.matricula
    FROM ralquileres
    JOIN rvehiculos ON ralquileres.matricula = rvehiculos.matricula
    WHERE ralquileres.idcliente = :idcliente AND ralquileres.fecha_devolucion IS NULL;
    ";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':idcliente', $_SESSION['idcliente']);
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

function finalquiler($matricula){
    datadb();
    $_SESSION['matricula']=$matricula;
    try {
        $conexion = conectar();
        $sql = "UPDATE ralquileres 
        SET fecha_devolucion = NOW()
        WHERE matricula = :matricula AND idcliente = :idcliente;        
        ";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':matricula', $matricula);
        $consulta->bindParam(':idcliente', $_SESSION['idcliente']);
        $consulta->execute();
        $conexion = NULL;
        consultaPrecio($matricula);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function estadoVehiculo(){
    datadb();   

     try {
        $conexion = conectar();
        $sql = "UPDATE rvehiculos 
        SET disponible = 'S'
        WHERE matricula = :matricula;        
        ";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':matricula', $matricula);
        $matricula=$_SESSION['matricula'];
        $consulta->execute();
        $conexion = NULL;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function consultaPrecio($matricula){
    datadb();
    $conexion = conectar();
    $sql = "SELECT preciobase from rvehiculos where matricula= :matricula;
    ";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':matricula', $matricula);
    $consulta->execute();
    $precio = $consulta->fetchColumn();
    $conexion = NULL;
    pintarPrecio($precio);
}
function pintarPrecio($precio){
    $minutos=intval(calcularPago());
    $_SESSION['pago']=$minutos*$precio;
echo "<table border='1'>";
echo "<td>Total a pagar:</td>";
echo "<td>".$_SESSION['pago']."</td>";
echo "</table>";
pago();
}

function calcularPago(){
    datadb();
    $conexion = conectar();
    $sql = "SELECT TIMESTAMPDIFF(MINUTE, fecha_alquiler, fecha_devolucion) AS minutos
    FROM ralquileres
    WHERE idcliente=:idcliente AND fechahorapago  IS NULL  AND matricula=:matricula;
    ";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':idcliente', $_SESSION['idcliente']);
    $consulta->bindParam(':matricula', $_SESSION['matricula']);
    $consulta->execute();
    $minutos = $consulta->fetchColumn();
    $conexion = NULL;
    return $minutos;
}

function pago(){
   
    $miObj = new RedsysAPI;
    $fuc = "999008881";
    $terminal = "1";
    $moneda = "978";
    $trans = "0";
    $url = "";
    $urlOK = "http://192.168.206.232/movilmad/hecho.php";
    $urlKO = "http://192.168.206.232/movilmad/mal.php";
    $id = time();
    $amount = strval($_SESSION['pago']);
    if (strpos($amount, '.')) {
        $amount = explode('.', $amount);
        $amount[1] = str_pad($amount[1], '2', '0', STR_PAD_RIGHT);
        $amount = $amount[0] . $amount[1];
    } else {
        $amount = $amount . '00';
    }
    $miObj->setParameter("DS_MERCHANT_AMOUNT", $amount);
    $miObj->setParameter("DS_MERCHANT_ORDER", $id);
    $miObj->setParameter("DS_MERCHANT_MERCHANTCODE", $fuc);
    $miObj->setParameter("DS_MERCHANT_CURRENCY", $moneda);
    $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $trans);
    $miObj->setParameter("DS_MERCHANT_TERMINAL", $terminal);
    $miObj->setParameter("DS_MERCHANT_MERCHANTURL", $url);
    $miObj->setParameter("DS_MERCHANT_URLOK", $urlOK);
    $miObj->setParameter("DS_MERCHANT_URLKO", $urlKO);

    $version = "HMAC_SHA256_V1";
    $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; 

    $params = $miObj->createMerchantParameters();
    $signature = $miObj->createMerchantSignature($kc);

    echoform($version, $params, $signature);
}
function echoform($version, $params, $signature){
    echo "<form name='frm'  action='https://sis-t.redsys.es:25443/sis/realizarPago' method='POST' target='_blank'>
        <input type='text'    name='Ds_SignatureVersion' value='$version'/></br>
        <input type='text'   name='Ds_MerchantParameters' value='$params'/></br>
        <input type='text'   name='Ds_Signature' value='$signature'/></br>
        <input type='submit'  value='Realizar Pago'>
      </form>";
}

function pagado(){
    session_start();
    var_dump($_SESSION);
    datadb();
    try {
        $conexion = conectar();
        $sql = "UPDATE ralquileres 
        SET fechahorapago = NOW(),preciototal = :precio
        WHERE matricula = :matricula AND idcliente = :idcliente AND preciototal IS NULL AND fechahorapago IS NULL;        
        ";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':matricula', $_SESSION['matricula']);
        $consulta->bindParam(':idcliente', $_SESSION['idcliente']);
        $consulta->bindParam(':precio', $_SESSION['pago']);
        $consulta->execute();
       
        echo "TODO BIEN";
        $conexion = NULL;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
function nopagado(){
    session_start();
    datadb();
    try {
        $conexion = conectar();
        $sql = "UPDATE ralquileres 
        SET preciototal = :precio
        WHERE matricula = :matricula AND idcliente = :idcliente AND preciototal IS NULL;        
        ";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':matricula', $_SESSION['matricula']);
        $consulta->bindParam(':idcliente', $_SESSION['idcliente']);
        $consulta->bindParam(':precio', $_SESSION['pago']);
        $consulta->execute();
        $conexion = NULL;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    try {
        $conexion = conectar();
        $sql = "UPDATE rclientes 
        SET pendiente_pago = pendiente_pago + :precio
        WHERE idcliente = :idcliente;        
        ";
        $consulta = $conexion->prepare($sql);
        $consulta->bindParam(':idcliente', $_SESSION['idcliente']);
        $consulta->bindParam(':precio', $_SESSION['pago']);
        $consulta->execute();
        $conexion = NULL;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>