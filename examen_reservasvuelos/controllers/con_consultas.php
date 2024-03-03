<?php
include_once './models/mo_consultas.php';

function check_session(){
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: ./index.php');
        exit;
    }
}
function reservas($dni){

    $todas_reservas = mostrar_reservas ($dni);
    return $todas_reservas;
}


function imprimir_reserva($todas_reservas){

    foreach($todas_reservas as $aray) { 
     
        echo "<option value=" . "'" . $aray["id_reserva"] . "'>" . $aray["id_reserva"] . "</option>";
    
    }
    echo "</select>" . "<br>"; 
}

function consulta_reserva($reserva){
    $todos_vuelos = datos_reservas($reserva);
}


function imprimir_vuelos($todos_vuelos){
    $dimension = count($todos_vuelos);
  
    echo "<table>";
    echo "<tr> <th>Aerolíneas</th> <th>Origen</th> <th>Destino</th> <th>Salida</th> <th>Llegada</th> <th>Asientos</th></tr>";
    for ($i=0; $i < $dimension; $i++) { 
        echo "<tr>";
        echo "<td>" . $todos_vuelos[$i]["nombre_aerolinea"] . "</td>";
        echo "<td>" . $todos_vuelos[$i]["origen"] . "</td>";
        echo "<td>" . $todos_vuelos[$i]["destino"] . "</td>";
        echo "<td>" . $todos_vuelos[$i]["fechahorasalida"] . "</td>";
        echo "<td>" . $todos_vuelos[$i]["fechahorallegada"] . "</td>";
        echo "<td>" . $todos_vuelos[$i]["num_asientos"] . "</td>";
        echo "</tr>";    
    }
    echo "</table>";

}

?>