<?php
session_start();
session_unset();
session_destroy();
setcookie("PHPSESSID","",time()-3600,"/");


header("Location: pe_login.php");
?>