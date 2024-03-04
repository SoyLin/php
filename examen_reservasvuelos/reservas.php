<?php
include_once './controllers/con_reservas.php';


	check_session();

	$todos_vuelos = mostrar_idVuelos();

	if (isset($_POST['agregar'])) {

		$vuelo_seleccionado = $_POST["vuelos"];
		$num_asientos = $_POST["asientos"];

	 	agregar_cesta($vuelo_seleccionado,$num_asientos);

    }

	if (isset($_POST['comprar'])) {
       
		$error = check_disponibilidad();

		if (empty($error)) {
			
			$totalPrecio = precio_total();
			$_SESSION["amount"] = $totalPrecio;
			pago();
		}

    }

	if (isset($_POST['vaciar'])) {

        destruir_carrito();
    }

	if (isset($_POST['volver'])) {
        header("Location: inicio.php");

    }

include_once './views/v_reservas.php';
?>