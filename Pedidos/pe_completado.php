<?php
session_start(); //recuperar phpsesionID

if(!isset($_SESSION["usuario"])) { //comprobar si ha pasado por login
    echo "Sesion no definida";  //si NO está establecida la variable -> no ha pasado por login -> es una copia de enlace
    echo "<pre>";
	print_r( $_SESSION);
	echo "</pre>";
} else {

echo "<pre>";
print_r( $_SESSION);
echo "</pre>";


try {
    //date() https://stackoverflow.com/questions/470617/how-do-i-get-the-current-date-and-time-in-php
    require_once'funciones.php';

#ESTABLECER CONEXIÓN
    $conn = conexion();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
#CALCULAR orderNumber 
    $orderNumber = $_SESSION["orderNumber"];
    echo "orderNumber: " . $orderNumber . "<br>";

#FECHA ACTUAL
    $fechaNow = date("Y-m-d");
    echo $fechaNow . "<br>";

#Obter customerNum almacenada en $_SESSION
    $customerNum = $_SESSION["usuario"];
    echo $customerNum . "<br>";

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

    $productCode = $_SESSION["carrito"][$i]['productCode']; //Sacar ProductCode del array indexado en cada iteración de for
    $cantidadPedido = $_SESSION["carrito"][$i]['cantidad']; //Sacar cantidad delproducto correpondiente en cada iteración de for
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


} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
 
}// } de else


?>