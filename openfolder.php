<?php

$file = $_GET["file"];
$user = $_COOKIE["user"];

require_once "config.php";

$sql = "SELECT dir FROM users WHERE password = ?";

if($stmt = mysqli_prepare($link, $sql)) {
	mysqli_stmt_bind_param($stmt, "s", $param_password);
	$param_password = $user;
	
	if(mysqli_stmt_execute($stmt)) {
		mysqli_stmt_store_result($stmt);
		
		if(mysqli_stmt_num_rows($stmt) == 1) {
			mysqli_stmt_bind_result($stmt, $userdir);
			
			if(mysqli_stmt_fetch($stmt)) {
				$_SESSION["id"] = $id;
                                $udir = $userdir;
			}
		}
	}
	mysqli_stmt_close($stmt);
}
mysqli_close($link);


if ($_COOKIE["currentdir"] == "root") {
    if (isset($_GET["extra"])) {
        if ($_GET["extra"] == "k") {
            $currentdir = "K:";
        } elseif ($_GET["extra"] == "share") {
            $currentdir = "K:";
            $file = "share";
        }
    } else {
        $currentdir = $udir;
    }
    
} else {
    $currentdir = $_COOKIE["currentdir"];
}
$currentdir = $currentdir."/".$file;
setcookie("currentdir",$currentdir,time()+7*24*60*60);
header("location: index.php");

?>

