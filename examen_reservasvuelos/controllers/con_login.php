<?php
include_once './models/mo_login.php';

function login($usuario) {
    login_DB($usuario);
}

function comprobarPass($usuario){
    $digitos_user = substr($usuario,0,4);
    return $digitos_user;
}


?>