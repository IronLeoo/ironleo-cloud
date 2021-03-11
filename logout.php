<?php

session_start();

$_SESSION = array();

session_destroy();

setcookie("user", "", time()-3600);
setcookie("currentdir", "", time()-3600);
header("location: login.php");
exit;

?>

