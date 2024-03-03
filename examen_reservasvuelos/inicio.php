<?php
include_once './controllers/con_inicio.php';


	check_session();
    if (isset($_POST['salir'])) {
        logoff();
    }
	
	if (isset($_POST['consultar'])) {
        header("Location: consultas.php");

    }

	// echo "<pre>";
	// print_r($_SESSION);
	// echo "</pre>";
   
include_once './views/v_inicio.php';

?>