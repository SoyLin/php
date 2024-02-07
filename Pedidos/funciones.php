<?php
//PDO
function conexion(){
    $servername = "localhost";
    $username = "root";
    $password = "rootroot";
    $dbname = "pedidos";//nombre de bases de datos, escribir el nombre correcto para conectar con el BBDD correspondiente

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    return $conn;
}
#¡MISMA FUNCIÓN PARA MULTIPLES TABLAS!
  //sacar max id
  function max_codigo ($conn,$ID_campo,$table){//hacer select MEDIANTE la conexión $conn que HABIAMOS ESTABLECIDO ANTERIORMENTE
    $stmt = $conn->prepare("SELECT MAX($ID_campo)as max_id FROM $table");
    $stmt->execute(); //excute
    $resultado = $stmt -> fetch(PDO::FETCH_ASSOC); //devuelve un array con un solo elemento
    $codigo = $resultado['max_id']; //sacar el valor de la posición "max_cod"
    return $codigo;

    // echo "<pre>";
    // print_r($resultado);
    // echo "</pre>";
}

    //listas: MOSTRAR los nombres pero envia el codigo  
    function list_cat($conn,$ID_campo_list,$Otros_campos,$table_list,$mensaje,$name_indice){
        $stmt = $conn->prepare("SELECT $ID_campo_list,$Otros_campos FROM $table_list");
        $stmt->execute(); //excute
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $multi_fila = $stmt->fetchAll();
        
        echo "<label for='list'>" . $mensaje . "</label>";
        echo "<select id='list' name=" . $name_indice . ">";
        foreach ($multi_fila as $aray) {
            echo "<option value=" . $aray[$ID_campo_list] . ">" . $aray[$Otros_campos] . "</option>";
            //muestra "NOMBRE" pero selecciona "ID" , porque es el valor que se almacena en value 

        }
        echo "</select>" . "<br>";
    }
    
//INCREMENTO DE CÓDIGO
function incremento($numero){
    $numero++;
    return $numero;
}

//LIMPIEZA DE DATOS
function limpieza($data) {
    $data = str_replace("  ","",$data); //he utilizado str_replace en lugar de trim para eliminar todos los espacios
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function limpieza_trim($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>