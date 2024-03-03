<?php
include "./db/datadb.php";
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