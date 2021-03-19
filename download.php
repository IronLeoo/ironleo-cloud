<?php
require_once "config.php";

$dlFile = $_GET["file"];
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
        $dlFolder = $udir;
    } else {
        $dlFolder = $dirk.$udir;
    }
} else {
    if ($udir == "C:") {
        $dlFolder = $_COOKIE["currentdir"];
    } else {
        $dlFolder = $dirk.$_COOKIE["currentdir"];
    }
}

$dlPath = $dlFolder."/".$dlFile;

if($count == 1) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    header('Content-Disposition: attachment; filename="'.basename($dlPath).'"');
    header('Content-Length: ' . filesize($dlPath));
    header('Pragma: public');

    flush();

    readfile($dlPath);

    die();
    
} else {
    header("location: login.php?error=nologin");
}
?>