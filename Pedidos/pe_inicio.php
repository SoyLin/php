<?php
session_start(); 

if(!isset($_SESSION["usuario"])) {
    echo "Sesion no definida";
    header("Location: pe_login.php");
} else {
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            margin-left: 40%;
            padding-top: 5%;
            font-size:20px;
        }
        div{
            padding-top: 1.5%;
        }
        a:visited{
            color: blueviolet;
        }
    </style>
    <title>Document</title>
</head>
<body>
<h3>MENÚ</h3>
    <div>
        <div class="menu" id="compra">
            <a href="./pe_altaped.php">1.COMPRA DE PRODUCTOS</a>
        </div>
        <div class="menu" id="consulta">
            <a href="./pe_consped.php">2.PEDIDOS REALIZADOS</a>
        </div>
        <div  class="menu" id="consulta">
            <a href="./pe_consprodstock.php">3.CONSULTA DE STOCK DE PRODUCTOS</a>
        </div>
        <div  class="menu" id="linea">
            <a href="./pe_constock.php">4.CONSULTA POR LÍNEA DE PRODUCTOS</a>
        </div>
    </div>
    <br><a href="pe_logout.php">Cerrar Sesión</a>
</body>
</html>
<?php

}
?>