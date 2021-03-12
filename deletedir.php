<?php
require_once "config.php";

$file = $_GET["file"];
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

if ($_COOKIE["currentdir"] == "root") {
    $dir = $udir;
} else {
    $dir = $_COOKIE["currentdir"];
}

$rmDir = $dir."/".$file;

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
    header("location: index.php");
} else {
    header("location:javascript://history.go(-1)");
}
?>