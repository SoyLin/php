<?php
include_once './models/mo_reservas.php';
include './api/apiRedsys.php';

function check_session(){
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ./index.php');
        exit;
    }
}

function agregar_cesta($vuelo_seleccionado,$num_asientos){

		if (isset($_SESSION["carrito"])){
			$dimensionAray = count($_SESSION["carrito"]);
			for ($i=0; $i <$dimensionAray; $i++) { 
				if ($vuelo_seleccionado == ($_SESSION["carrito"][$i]['id_vuelo'])) {
					$indiceAray = $i;    
				}
			}
			
			if (isset($indiceAray)) {
				$_SESSION["carrito"][$indiceAray]["asientos"] = ($num_asientos +  $_SESSION["carrito"][$indiceAray]["asientos"]);
			}else {
				$_SESSION['carrito'][]=array("id_vuelo"=>$vuelo_seleccionado, "asientos"=> $num_asientos);
			}
	
		}else {
	
			$carrito_aray = array(
				array("id_vuelo" => $vuelo_seleccionado, "asientos" => $num_asientos),
			);
			$_SESSION["carrito"] = $carrito_aray;
		}
	
}

function destruir_carrito(){
    unset($_SESSION['carrito']);
}

function check_disponibilidad(){
    $error = array();

    $totalAray = count($_SESSION["carrito"]);

    for ($i=0; $i <= ($totalAray-1); $i++) { 

        $vuelo_seleccionado = $_SESSION["carrito"][$i]['id_vuelo'];
        $num_asientos = $_SESSION["carrito"][$i]['asientos'];


        $asiento_disponible = asientos_disponible($vuelo_seleccionado);//llamar función para obtener numero de asientos disponibles
        
        if ($asiento_disponible< $num_asientos) {
            array_push($error, "vuelo:  $vuelo_seleccionado no quedan suficiente asiento, asientos disponibles: $asiento_disponible");

        }
    }

    return $error;
}

function precio_total(){

	$totalAray = count($_SESSION["carrito"]);
	$totalPrecio = 0;

    for ($i=0; $i <= ($totalAray-1); $i++) { 

        $vuelo_seleccionado = $_SESSION["carrito"][$i]['id_vuelo'];
        $num_asientos = $_SESSION["carrito"][$i]['asientos'];


        $precio = precio_asiento($vuelo_seleccionado);//función para obtener precio de 1 asiento
        
       	$totalPrecio += $precio*$num_asientos;
    }

    return $totalPrecio;
}


function pago(){
   
    $miObj = new RedsysAPI;
    $fuc = "999008881";
    $terminal = "1";
    $moneda = "978";
    $trans = "0";
    $url = "";
    $urlOK = "http://localhost/examen_reservasvuelos/exitoso.php";
    // $urlOK = "http://192.168.206.214/examen_reservasvuelos/exitoso.php";
    $urlKO = "http://localhost/examen_reservasvuelos/denegado.php";
    $id = time();
    $amount= $_SESSION["amount"]*100;

    $miObj->setParameter("DS_MERCHANT_AMOUNT", $amount);
    $miObj->setParameter("DS_MERCHANT_ORDER", $id);
    $miObj->setParameter("DS_MERCHANT_MERCHANTCODE", $fuc);
    $miObj->setParameter("DS_MERCHANT_CURRENCY", $moneda);
    $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $trans);
    $miObj->setParameter("DS_MERCHANT_TERMINAL", $terminal);
    $miObj->setParameter("DS_MERCHANT_MERCHANTURL", $url);
    $miObj->setParameter("DS_MERCHANT_URLOK", $urlOK);
    $miObj->setParameter("DS_MERCHANT_URLKO", $urlKO);

    $version = "HMAC_SHA256_V1";
    $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; 

    $params = $miObj->createMerchantParameters();
    $signature = $miObj->createMerchantSignature($kc);

    echo "<form name='frm'  action='https://sis-t.redsys.es:25443/sis/realizarPago' method='POST' target='_blank'>
            <input type='text'    name='Ds_SignatureVersion' value='$version'/></br>
            <input type='text'   name='Ds_MerchantParameters' value='$params'/></br>
            <input type='text'   name='Ds_Signature' value='$signature'/></br>
            <input type='submit'  value='Realizar Pago'>
        </form>";
}

?>