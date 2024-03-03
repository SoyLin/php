<?php
include_once './models/mo_login.php';

function comprobarPass($usuario,$passwd){
    $digitos_user = substr($usuario,0,4);
    return $digitos_user;
}


function login($usuario) {
    login_DB($usuario);
}

?>