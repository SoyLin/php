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
    <title>COMPRAS</title>
</head>
<body>
      
<div class="menu">
		<ul>
			<li><a href="./pe_inicio.php" >Menú</a></li>
			<li><a href="./pe_altaped.php" id="current">Comprar</a></li>
            <li><a href="./pe_consped.php">Pedidos</a></li>
            <li><a href="./pe_consprodstock.php">Stock</a></li>
            <li><a href="./pe_constock.php">Categoria</a></li>
			<li><a href="pe_logout.php">Cerrar sesión</a></li>
		</ul>
</div>
<h2>Consulta de Historia de Pedidos</h2>


<?php
        require_once 'funciones.php';

     try {
        $conn = conexion();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // echo "<pre>";
        // print_r($_SESSION);
        // echo "</pre>";

#obtener CUSTOMERNUMBER almacenada en $_SESSION
        $usuario = $_SESSION["usuario"];

#Pedidos realizados por el cliente
        $stmt = $conn->prepare("SELECT orderNumber ,orderDate,status FROM orders WHERE customerNumber = :customerNumber ORDER BY orderNumber DESC ");
        $stmt->bindParam(':customerNumber', $usuario);  //todos los pedidos que TENGA este CUSTOMER NUMBER
        $stmt->execute(); //excute
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $multi_pedido = $stmt->fetchAll();
  

        $dimension_pediod = count($multi_pedido);
        echo "cantidad: " . $dimension_pediod . "<br>"; 

     
#RECORRES TODOS LOS PEPIDOS REALIZADOS
        for ($i=0; $i <$dimension_pediod ; $i++) { 
            $orderNumber = $multi_pedido[$i]["orderNumber"]; //almacenar ordernumber del array correspondiente en cada iteración
          
        #IMPRIMIR TABLA       
            echo "<table>";
            echo "<tr> <th>OrderNumber</th> <th>OrderDate</th> <th colspan='2'>Status</th> </tr>";
            echo "<tr>"; // nueva fila
            echo "<td>" . $multi_pedido[$i]["orderNumber"] . "</td>";
            echo "<td>" . $multi_pedido[$i]["orderDate"] . "</td>";
            echo "<td>" . $multi_pedido[$i]["status"] . "</td>";
            echo "</tr>";


            $stmt2 = $conn->prepare("SELECT orderLineNumber,productName,quantityOrdered,priceEach FROM orderdetails,products WHERE orderdetails.productCode=products.productCode AND orderNumber = :orderNumber ORDER BY orderLineNumber");
            $stmt2->bindParam(':orderNumber', $orderNumber);  //con el OrderNumber obtenido -> sacamos todos los productos con ese OrderNumber
            $stmt2->execute(); //excute
            $stmt2->setFetchMode(PDO::FETCH_ASSOC);
            $multi_producto = $stmt2->fetchAll(); //almacenar todas las FILAS(resultado) en un array 
            
#RECORRES TODOS LOS PRODUCTOS ALMACENADAS EN EL ARRAY $multi_producto
            echo "<tr> <th>orderLineNumber</td> <th>productName</th> <th>quantityOrdered</th> <th>priceEach</th> </tr>";
            for ($j=0; $j < (count($multi_producto)) ; $j++) { 
                echo "<tr>";        // nueva fila
                echo "<td>" . $multi_producto[$j]["orderLineNumber"] . "</td>";
                echo "<td>" . $multi_producto[$j]["productName"] . "</td>";
                echo "<td>" . $multi_producto[$j]["quantityOrdered"] . "</td>";
                echo "<td>" . $multi_producto[$j]["priceEach"] . "</td>";
                echo "</tr>";            
            }

            echo "</table>" . "<br>";
        }

    echo "<pre>";
    print_r($multi_pedido);
    echo "</pre>";


    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
</body>
</html>