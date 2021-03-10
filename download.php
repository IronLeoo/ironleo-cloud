<?php
require_once "config.php";

$dlFile = $_GET["currentdir"];
$getUser = $_COOKIE["user"];

if($stmt = mysqli_prepare($link, "SELECT * FROM users where password = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $param_password);
    $param_password = $getUser;
    
    if(mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);

if($count == 1) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    header('Content-Disposition: attachment; filename="'.basename($dlFile).'"');
    header('Content-Length: ' . filesize($dlFile));
    header('Pragma: public');

    flush();

    readfile($dlFile);

    die();
    
} else {
    header("location: login.php?error=nologin");
}
?>