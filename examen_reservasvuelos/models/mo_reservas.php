<?php
include_once "./db/vconfig.php";


function mostrar_idVuelos (){
    $conn = conexion();
    try {
        $stmt = $conn->prepare("SELECT id_vuelo,origen,destino from vuelos");
        
        $stmt->execute();
        $todos_vuelos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $todos_vuelos;
       

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function asientos_disponible($vuelo_seleccionado){
    $conn = conexion();

    try {
        $stmt = $conn->prepare("SELECT asientos_disponibles from vuelos 
        where id_vuelo= :id_vuelo;");
        $stmt->bindParam(':id_vuelo', $vuelo_seleccionado);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $asiento_disponible = $resultado["asientos_disponibles"];

        return $asiento_disponible;
       

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


function precio_asiento($vuelo_seleccionado){
    $conn = conexion();

    try {
        $stmt = $conn->prepare("SELECT precio_asiento from vuelos 
        where id_vuelo= :id_vuelo;");
        $stmt->bindParam(':id_vuelo', $vuelo_seleccionado);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $precio = $resultado["precio_asiento"];

        return $precio;
       

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function obtener_idReserva  (){//hacer select MEDIANTE la conexión $conn que HABIAMOS ESTABLECIDO ANTERIORMENTE
    $conn = conexion();

    try {
    $stmt = $conn->prepare("SELECT MAX($id_reserva)as max_id FROM reservas");
    $stmt->execute(); //excute
    $resultado = $stmt -> fetch(PDO::FETCH_ASSOC); //devuelve un array con un solo elemento
    $numero = $resultado['max_id']; //sacar el valor de la posición "max_cod"

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    return $numero;
}

function incremento($numero){
    $numero = substr($numero,1);
    if ($numero==0) {
        $numero=0;
    }
    $numero++;
    $numero = str_pad($numero,3,"0",STR_PAD_LEFT); //rellenar con 0s hasta alcanzar la longitud que hemos indicado
    return "R" . $numero;
}


function insert_reserva(){
    $conn = conexion();

    
}
?>