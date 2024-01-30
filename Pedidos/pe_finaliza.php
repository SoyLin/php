<?php
session_start(); //recuperar phpsesionID

if(!isset($_SESSION["usuario"])) { //comprobar si ha pasado por login
    echo "Sesion no definida";  //si NO está establecida la variable -> no ha pasado por login -> es una copia de enlace
    header("Location: pe_login.php"); //DEBE loguearse primero
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<?php
    require_once 'menu.html';
?>
<!-- <a href='./pe_altaped.php'>Volver a la compra</a><br><br> -->
<div class="formulario">

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
        <label>Introduce el número de pago: 
            <input type="text" name="cuenta">
        </label><br><br>
        <input type="submit" name="pagar" value="Pagar">
        <input type="submit" name="volver" value="volver a la compra" formaction="pe_altaped.php">
        <input type="submit" name="destruir" value="Eliminar carrito">

    </form>
</div>
</body>
</html>

<?php
#Mostrar carrito
if (isset ($_POST["destruir"])) {
    unset($_SESSION['carrito']);
}

echo "<pre>";
print_r( $_SESSION);
echo "</pre>";

if (isset ($_POST["pagar"])) {

try {
    //date() https://stackoverflow.com/questions/470617/how-do-i-get-the-current-date-and-time-in-php
    require_once'funciones.php';

#ESTABLECER CONEXIÓN
    $conn = conexion();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

#CALCULAR orderNumber 
    $ID_campo = "orderNumber";
    $table = "orders";
    $orderNumber = max_codigo($conn,$ID_campo,$table); //función sacar max id
    $orderNumber = incremento($orderNumber); //incrementar +1
    echo "orderNumber: " . $orderNumber . "<br>";

#FECHA ACTUAL
    $fechaNow = date("Y-m-d");
    echo $fechaNow . "<br>";

#Obter customerNum almacenada en $_SESSION
    $customerNum = $_SESSION["usuario"];
    echo $customerNum . "<br>";

#VALIDACIÓN correcta=realiza pedido; error=denega;
    $cuenta = strtoupper($_POST["cuenta"]);

if (!preg_match("/^[A-Z]{2}[0-9]{5}$/",$cuenta)) { //comprueba si cumple el formato
    echo "error: número de pago no válida";
}else{//formato válido
    echo "cuenta correcta! <br>";

#INSERTAR en tabla order
    $stmt = $conn->prepare("INSERT INTO orders (orderNumber,orderDate,requiredDate,shippedDate,status,comments,customerNumber) VALUES (:orderNumber,:orderDate,:requiredDate,NULL,'In Process',NULL,:customerNumber)");
    $stmt->bindParam(':orderNumber', $orderNumber);  
    $stmt->bindParam(':orderDate', $fechaNow);    
    $stmt->bindParam(':requiredDate', $fechaNow);    
    $stmt->bindParam(':customerNumber', $customerNum);    
    $stmt->execute(); //excute


#INSERTAR en tabla orderdetails
    $totalAray = count($_SESSION["carrito"]);
    // echo "total Array : " . $totalAray . "<br>";
    
    $precioTotal = 0; //precio Total inicialmente 0

    $contador = 1;
    
    for ($i=0; $i <= ($totalAray-1); $i++) { 

    $productCode = $_SESSION["carrito"][$i]['productCode'];
    $cantidadPedido = $_SESSION["carrito"][$i]['cantidad'];
    echo "cantidad: " . $cantidadPedido . " ";

    $stmt4 = $conn->prepare("SELECT buyPrice FROM `products` WHERE productCode= :productCode");
    $stmt4->bindParam(':productCode', $productCode);   
    $stmt4->execute();
    $resultado =  $stmt4 -> fetch(PDO::FETCH_ASSOC);
    // echo "<pre>";
    // print_r($resultado);
    // echo "</pre>";
    $precio = $resultado["buyPrice"];
    echo "precio: " . $precio .  "<br>";

    $precioAray = $cantidadPedido*$precio;

    $precioTotal +=$precioAray;



    $stmt2 = $conn->prepare("INSERT INTO orderdetails (orderNumber,productCode,quantityOrdered,priceEach,orderLineNumber) VALUES (:orderNumber,:productCode,:quantityOrdered,:priceEach,:orderLineNumber)");
    $stmt2->bindParam(':orderNumber', $orderNumber);  
    $stmt2->bindParam(':productCode', $productCode);    
    $stmt2->bindParam(':quantityOrdered', $cantidadPedido);    
    $stmt2->bindParam(':priceEach', $precio);    
    $stmt2->bindParam(':orderLineNumber', $contador);    
    $stmt2->execute(); //excute

  

    $stmt3 = $conn->prepare("UPDATE products SET quantityInStock = (quantityInStock - :quantityOrdered) WHERE productCode= :productCode");
    $stmt3->bindParam(':productCode', $productCode);    
    $stmt3->bindParam(':quantityOrdered', $cantidadPedido);    
    $stmt3->execute(); //excute

    $contador++;

}//} de for
    echo "Total del carrito: " . $precioTotal;
    #INSERTAR TABLA payments
    // SELECT SUM((quantityOrdered*priceEach))as total FROM `orderdetails`where orderNumber="10100";
    $stmt5 = $conn->prepare("INSERT INTO payments (customerNumber,checkNumber,paymentDate,amount) VALUES (:customerNumber,:checkNumber,:paymentDate,:amount)");
    $stmt5->bindParam(':customerNumber', $customerNum);  
    $stmt5->bindParam(':checkNumber', $cuenta);    
    $stmt5->bindParam(':paymentDate', $fechaNow);    
    $stmt5->bindParam(':amount', $precioTotal);   
    $stmt5->execute(); //excute

    unset($_SESSION['carrito']);

} //} de validacion

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}//} de if enviado
 
}
?>