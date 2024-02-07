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
    $_SESSION["orderNumber"] = $orderNumber;

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

    $totalAray = count($_SESSION["carrito"]); //contar la dimensión del array "carrito"
    // echo "total Array : " . $totalAray . "<br>";
    
    $precioTotal = 0; //precio Total inicialmente 0

    $contador = 1;
    
for ($i=0; $i <= ($totalAray-1); $i++) { //Recorrer el array "carrito"

    $productCode = $_SESSION["carrito"][$i]['productCode']; //Sacar ProductCode del array indexado en cada iteración de for
    $cantidadPedido = $_SESSION["carrito"][$i]['cantidad']; //Sacar cantidad delproducto correpondiente en cada iteración de for
    echo "cantidad: " . $cantidadPedido . " ";

    $stmt4 = $conn->prepare("SELECT buyPrice FROM `products` WHERE productCode= :productCode");
    $stmt4->bindParam(':productCode', $productCode);   
    $stmt4->execute();
    $resultado =  $stmt4 -> fetch(PDO::FETCH_ASSOC); //BBDD devuelve el resultado en un array asociativo
    $precio = $resultado["buyPrice"]; // sacamos el valor de "buyPrice" del array $resultado
    echo "precio: " . $precio .  "<br>";

    $precioAray = $cantidadPedido*$precio; //número de producto por el precio que tiene = total de ESE PRODUCTO 

    $precioTotal +=$precioAray; //Calcular total del CARRITO = IR SUMANDO EL PRECIO TOTAL de TODOS LOS PRODUCTOS 


}//} de for
    
    $_SESSION["amount"] = $precioTotal; //almacenar Total de carrito en $SESSION
    header("Location: redsys/ejemploGeneraPet.php"); 
    exit();

} //} de validacion

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

}//} de if enviado
 
}
?>