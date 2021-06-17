<?php
require_once "config.php";


$fileName = $_GET["filen"];
$newName = $_GET["newname"];
$getUser = $_COOKIE["user"];
$dirk = "K:";

if($stmt = mysqli_prepare($link, "SELECT dir FROM users where password = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $param_password);
    $param_password = $getUser;
    
    if(mysqli_stmt_execute($stmt)) {
		mysqli_stmt_store_result($stmt);
		
		if(mysqli_stmt_num_rows($stmt) == 1) {
			mysqli_stmt_bind_result($stmt, $userdir);
			
			if(mysqli_stmt_fetch($stmt)) {
                                $udir = $userdir;
                                $count = 1;
			}
		}
	}
    mysqli_stmt_close($stmt);
}
mysqli_close($link);

if ($_COOKIE["currentdir"] == "root") {
    if ($udir == "C:") {
        $fileDir = $udir;
    } else {
        $fileDir = $dirk.$udir;
    }
} else {
    if ($udir == "C:") {
        $fileDir = $_COOKIE["currentdir"];
    } else {
        $fileDir = $dirk.$_COOKIE["currentdir"];
    }
}

$filePath = $fileDir."/".$fileName;

session_start();

if($count == 1) {
    if (is_dir($filePath) == true) {
        rename($filePath, $fileDir."/".$newName);
        header("location: index.php");
    } elseif (is_dir($filePath) == false){
        rename($filePath, $fileDir."/".$newName.".".pathinfo($filePath, PATHINFO_EXTENSION));
        header("location: index.php");
    }
} else {
    header("index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rename</title>
    <meta name="language" content="de" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1">		
    <meta name="description" content="IronLeo File Rename" />					
    <link href="style.css" rel="stylesheet"/>
    <style type="text/css">
	body{ font: 14px sans-serif; }
	.wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
</html>