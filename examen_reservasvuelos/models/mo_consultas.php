<?php
include_once "./db/vconfig.php";


function mostrar_reservas ($dni){
    $conn = conexion();
    try {
        $stmt = $conn->prepare("SELECT id_reserva from reservas where dni_pasajero= :dni;");
        $stmt->bindParam(':dni', $dni);

        $stmt->execute();
        $todas_reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $todas_reservas;
       

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function datos_reservas($reserva,$conn){

    try {
        $stmt = $conn->prepare("SELECT nombre_aerolinea,origen,destino,fechahorasalida,fechahorallegada,num_asientos from vuelos,reservas,aerolineas where id_reserva= :reserva and reservas.id_vuelo=vuelos.id_vuelo and vuelos.id_aerolinea = aerolineas.id_aerolinea;");
        $stmt->bindParam(':reserva', $reserva);

        $stmt->execute();
        $todos_vuelos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $todos_vuelos;
       

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>