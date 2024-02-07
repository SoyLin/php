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
<?php
    require_once 'menu.html';
?>
<h2>Compra de productos</h2>
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
    <br><label>Cantidad:
        <input type="text" name="cantidad">
    </label><br>
    <br><input type="submit" name="aniadir" value="añadir carrito">
    <input type="submit" name="finaliza" value="finalizar compra" formaction="pasarela_prueba.php">
    <input type="submit" name="destruir" value="Eliminar carrito">

</form>
</div>
</body>
</html>

<?php
if (isset ($_POST["destruir"])) {
    unset($_SESSION['carrito']);
}

if (isset ($_POST["aniadir"])) {
//    unset($_SESSION["carrito"]); //destruir un valor especifico de session


    $pro_seleccionado = $_POST["pro_seleccionado"];
    $cantidad = $_POST["cantidad"];
    
    if (isset($_SESSION["carrito"])){
        $dimensionAray = count($_SESSION["carrito"]);
        for ($i=0; $i <= ($dimensionAray-1); $i++) { 
            if ($pro_seleccionado == ($_SESSION["carrito"][$i]['productCode'])) {
                $indiceAray = $i;    
            }
        }
        
        if (isset($indiceAray)) {
            $_SESSION["carrito"][$indiceAray]["cantidad"] = ($cantidad +  $_SESSION["carrito"][$indiceAray]["cantidad"]);
        }else {
            $_SESSION['carrito'][]=array("productCode"=>$pro_seleccionado, "cantidad"=> $cantidad);
        }

    }else {

        $carrito_aray = array(
            array("productCode" => $pro_seleccionado, "cantidad" => $cantidad),
        );
        $_SESSION["carrito"] = $carrito_aray;
    }

  
    // if(empty($_SESSION["carrito"])) {
    //     $carrito_aray = array(
    //         array("productCode" => $pro_seleccionado, "cantidad" => $cantidad),
    //     );
    //     $_SESSION["carrito"] = $carrito_aray;

    // }else {
     
    // }
 

    echo "<pre>";
    print_r( $_SESSION);
    echo "</pre>";
    

}
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
 
}
?>
