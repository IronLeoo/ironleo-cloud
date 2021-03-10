<?php
require_once "config.php";

$rmDir = $_GET["currentdir"];
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
    if ($handle = opendir($rmDir)) {
            while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
                    unlink($rmDir.'/'.$file);
                }
            }
    closedir($handle);
    }
    
    rmdir($rmDir);
    header("location:javascript://history.go(-1)");
} else {
    header("location:javascript://history.go(-1)");
}
?>