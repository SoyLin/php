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

    <title>STOCK</title>
</head>
<body>
<?php
    require_once 'menu.html';
?>

<h2>Consulta de Stock</h2>
<div class="formulario">
<form  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
<?php
    require_once 'funciones.php';

     try {
        $conn = conexion();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("SELECT products.productCode,productName FROM products WHERE quantityInStock >0");
        $stmt->execute(); //excute
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $multi_fila = $stmt->fetchAll();

        // echo "<pre>";
        // print_r($multi_fila);
        // echo "</pre>";  
            
        echo "<label for='pro'> Selecciona producto</label>";
         
        echo "<select name='pro_seleccionado' id='pro'>";
        foreach($multi_fila as $aray) { 
         
            echo "<option value= " . $aray["productCode"]. ">" . $aray["productName"] . "</option>";
        //selecciona codigo pero muestra el nombre
        }
        echo "</select>" . "<br>"; 
        
       
   
?>
    <br>
    <input type="submit" value="Consultar">

</form>
</div>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"]=="POST") {

    $pro_seleccionado = $_POST["pro_seleccionado"];
      
    $stmt2 = $conn->prepare("SELECT productName,quantityInStock FROM products WHERE productCode =:productCode");
    $stmt2->bindParam(':productCode', $pro_seleccionado); 
    $stmt2->execute(); //excute

    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $resultado = $stmt2->fetch();
    
    echo "<pre>";
    print_r($resultado);
    echo "</pre>";

    echo "<table>";
    echo "<tr> <th>Product Name</th> <th>Stock</th></tr>";
    echo "<tr>";
    foreach ($resultado as $key => $value) {
       
        echo "<td>" . $value . "</td>";

    }
    echo "</tr>";
    
}
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
 
}
?>