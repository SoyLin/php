<?php
$cuenta = "AA49997";

 if (!preg_match("/^[A-Z]{2}[0-9]{5}$/",$cuenta)) { //comprueba si cumple el formato
    echo "error: número cuenta no válida";
}else{//formato válido
    echo "correcta!";
}
?>