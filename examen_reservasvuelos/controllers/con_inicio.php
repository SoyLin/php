<?php

function check_session(){
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ./index.php');
        exit;
    }
}
function logoff(){
    session_unset();
    session_destroy();
    $cookies = $_COOKIE;
    foreach ($cookies as $cookie_name => $cookie_value) {
        setcookie($cookie_name, '', time() - 3600, '/');
    }
    header('Location: ./index.php');
    exit;
}
?>