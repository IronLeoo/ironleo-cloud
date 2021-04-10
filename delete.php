<?php
require_once "config.php";

$rmFile = $_GET["file"];
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

if ($_COOKIE["currentdir"] == "root") {
    if ($udir == "C:") {
        $rmDir = $udir;
    } else {
        $rmDir = $dirk.$udir;
    }
} else {
    if ($udir == "C:") {
        $rmDir = $_COOKIE["currentdir"];
    } else {
        $rmDir = $dirk.$_COOKIE["currentdir"];
    }
}

$rmPath = $rmDir."/".$rmFile;
$shareRemove = false;

if($stmt = mysqli_prepare($link, "SELECT * FROM share where path = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $param_path);
    $param_path = $rmPath;
    
    if(mysqli_stmt_execute($stmt)) {
		mysqli_stmt_store_result($stmt);
		
		if(mysqli_stmt_num_rows($stmt) == 1) {
                    $shareRemove = true;
                } else {
                    $shareRemove = false;
                }
    }
    mysqli_stmt_close($stmt);
}

if($count == 1) {
    if($shareRemove == true) {
        if($stmt = mysqli_prepare($link, "DELETE FROM share WHERE path = ?")) {
            mysqli_stmt_bind_param($stmt, "s", $param_path);
            $param_path = $rmPath;
            
            if(mysqli_stmt_execute($stmt)) {}
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
    
    unlink($rmPath);
    header("location: index.php");
} else {
    header("location: index.php");
    mysqli_close($link);
}
?>