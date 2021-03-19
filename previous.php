<?php

require_once "config.php";
$previousdir = $_COOKIE["currentdir"];
$getUser = $_COOKIE["user"];

if($stmt = mysqli_prepare($link, "SELECT dir FROM users where password = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $param_password);
    $param_password = $getUser;
    
    if(mysqli_stmt_execute($stmt)) {
		mysqli_stmt_store_result($stmt);
		
		if(mysqli_stmt_num_rows($stmt) == 1) {
			mysqli_stmt_bind_result($stmt, $userdir);
			
			if(mysqli_stmt_fetch($stmt)) {
                                $udir = $userdir;
			}
		}
	}
    mysqli_stmt_close($stmt);
}
mysqli_close($link);

if ($previousdir !== "C:" & $previousdir !== "K:" & $previousdir !== "root" & $previousdir !== $udir) {
    while (substr($previousdir, -1) != "/") {
        $previousdir = substr_replace($previousdir, "", -1);
    }

    $previousdir = substr_replace($previousdir, "", -1);
    if ($previousdir == "C:" || $previousdir == "K:" || $previousdir == $udir) {
        $previousdir = "root";
    }
    setcookie("currentdir",$previousdir,time()+7*24*60*60);
    header("location: index.php");
} else {
    header("location: index.php");
}

?>

