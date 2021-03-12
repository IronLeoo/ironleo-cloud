<?php
require "config.php";

$srcDir = $_COOKIE["currentdir"];
$getUser = $_COOKIE["user"];

if($stmt = mysqli_prepare($link, "SELECT dir FROM users where password = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $param_password);
    $param_password = $getUser;
    
    if(mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        
        if(mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $userdir);
			
		if(mysqli_stmt_fetch($stmt)) {
                    $_SESSION["id"] = $id;
                    $udir = $userdir;
                    $count = 1;
		}
	}
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);

if ($srcDir == "root") {
    $srcDir = $udir."/New Folder";
} else {
    $srcDir = $_COOKIE["currentdir"]."/New Folder";
}

if($count == 1) {

    mkdir($srcDir);
    header("location: index.php");
} else {
    header("location:javascript://history.go(-1)");
}
?>