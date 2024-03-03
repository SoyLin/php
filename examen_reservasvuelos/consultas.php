<?php
include_once './controllers/con_consultas.php';


	check_session();

	$todas_reservas = reservas($_SESSION["dni"]);

include_once './views/v_consultas.php';
  
	if (isset($_POST['consultar'])) {
       
		$reserva = $_POST["reserva"];

		consulta_reserva($reserva);
		imprimir_vuelos($todos_vuelos);
    }
	
	if (isset($_POST['volver'])) {
        header("Location: inicio.php");

    }

	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
   


?>