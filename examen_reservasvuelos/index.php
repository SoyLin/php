<?php
include_once './controllers/con_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario']) && isset($_POST['password'])) {
   
    $digitos_user = comprobarPass($_POST['usuario']);
  

    if ($digitos_user == $_POST['password']) {
        login($_POST['usuario']);
    }else{
        echo "contraseña mal escrita";
    }
   
}
include_once './views/v_index.php';
?>
