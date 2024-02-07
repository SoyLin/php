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

    <title>Categoria</title>
</head>
<body>
<?php
    require_once 'menu.html';
?>

<h2>Consulta por línea de producto</h2>
<div class="formulario">
<form  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
<?php
    require_once 'funciones.php';

     try {
        $conn = conexion();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("SELECT productLine FROM productlines");
        $stmt->execute(); //excute
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $multi_fila = $stmt->fetchAll();

        // echo "<pre>";
        // print_r($multi_fila);
        // echo "</pre>";  
            
        echo "<label for='cat'> Selecciona categoria:</label>";
         
        echo "<select name='cat_seleccionado' id='pro'>";
        foreach($multi_fila as $aray) { 
         
            echo "<option value=" . "'" . $aray["productLine"] . "'>" . $aray["productLine"] . "</option>";
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

    $cat_seleccionado = $_POST["cat_seleccionado"];
    echo $cat_seleccionado;
    
    // $cat_seleccionado = "Classiccars";
      
    $stmt2 = $conn->prepare("SELECT products.productName,quantityInStock FROM products,productlines WHERE products.productLine = productlines.productLine AND products.productLine = :productLine");
    $stmt2->bindParam(':productLine', $cat_seleccionado); 
    $stmt2->execute(); //excute

    $stmt2->setFetchMode(PDO::FETCH_ASSOC);
    $line_stock = $stmt2->fetchAll();
    
    echo "<pre>";
    print_r($line_stock);
    echo "</pre>";

    $dimension = count($line_stock);
    echo "longitud: " . $dimension . "<br>"; 

    echo "<table>";
    echo "<tr> <th>Product Name</th> <th>Stock</th></tr>";
    for ($i=0; $i < $dimension; $i++) { 
        echo "<tr>";
        echo "<td>" . $line_stock[$i]["productName"] . "</td>";
        echo "<td>" . $line_stock[$i]["quantityInStock"] . "</td>";
        echo "</tr>";    
    }
    echo "</table>";
    
}
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
 
}
?>