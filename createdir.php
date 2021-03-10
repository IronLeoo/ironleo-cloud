<?php
require_once "config.php";

$srcDir = $_GET["currentdir"]."/New Folder";
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

    mkdir($srcDir);
    header("location:javascript://history.go(-1)");
} else {
    header("location:javascript://history.go(-1)");
}
?>