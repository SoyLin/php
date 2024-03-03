<?php
include_once './controller/controlwelcome.php';

    checkUserLogin();
    if (isset($_POST['logoff'])) {
        logoff();
    }
   

include_once './view/movwelcome.php';
?>