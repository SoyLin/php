<?php
include "./db/vconfig.php";


function login_DB($usuario){
    $conn = conexion();

    try {
        $stmt = $conn->prepare("SELECT dni,nombre,email from pasajero where dni= :dni;");
        $stmt->bindParam(':dni', $usuario);

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($resultado)) {
            session_start();
            $_SESSION["usuario"] = $resultado["nombre"];
            $_SESSION["email"] = $resultado["email"];
            $_SESSION["fecha"] = date("Y/m/d");
            $_SESSION["dni"] = $usuario;
            header("Location: ./inicio.php");
            exit();
        }else {
            echo "Usuario o contraseña mal escrito";
            
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}

?>