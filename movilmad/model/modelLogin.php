<?php
include "./db/datadb.php";
function login($user, $pass){
    $conn = conectar();
    try {
        $stmt = $conn->prepare("SELECT nombre from rclientes where email= :usuario AND idcliente =:contra;");
        $stmt->bindParam(':usuario', $user);
        $stmt->bindParam(':contra', $pass);
        $stmt->execute();
        $info = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($info)) {
            $name = $info[0]['nombre'];
            session_start();
            $_SESSION['email'] = $user;
            $_SESSION['nombre'] = $name;
            $_SESSION['idcliente'] = $pass;
            header('Location: Welcome.php');
            exit;
        } else {
            echo "Usuario o contraseña incorrectos";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
}

function logoff(){
    session_unset();
    session_destroy();
    $cookies = $_COOKIE;
    foreach ($cookies as $cookie_name => $cookie_value) {
        setcookie($cookie_name, '', time() - 3600, '/');
    }
    header('Location: nologin.html');
    exit;
    }
?>