<?php
include_once './controllers/con_consultas.php';


	check_session();
	$todas_reservas = mostrar_idReservas ($_SESSION["dni"]);


  
	if (isset($_POST['consultar'])) {
       
		$reserva = $_POST["reserva"];

		$vuelo_info = datos_reservas($reserva);

    }
	
	if (isset($_POST['volver'])) {
        header("Location: inicio.php");

    }

include_once './views/v_consultas.php';
?>