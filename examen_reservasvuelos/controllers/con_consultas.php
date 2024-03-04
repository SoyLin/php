<?php
include_once './models/mo_consultas.php';

function check_session(){
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ./index.php');
        exit;
    }
}


?>