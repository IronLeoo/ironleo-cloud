<?php
require_once "config.php";

$file = $_GET["file"];
$getUser = $_COOKIE["user"];
$dirk = "K:";
echo $file;


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
    if ($udir == "C:") {
        $dir = $udir;
    } else {
        $dir = $dirk.$udir;
    }
} else {
    if ($udir == "C:") {
        $dir = $_COOKIE["currentdir"];
    } elseif (strpos($_COOKIE["currentdir"], "K:/share") !== false) {
        $dir = $_COOKIE["currentdir"];
    } else {
        $dir = $dirk.$_COOKIE["currentdir"];
    }
}

$rmDir = $dir."/".$file;

 function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir);
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
           rrmdir($dir. DIRECTORY_SEPARATOR .$object);
         else
           unlink($dir. DIRECTORY_SEPARATOR .$object); 
       } 
     }
     rmdir($dir); 
   } 
 }

if($count == 1) {
    rrmdir($rmDir);
    header("location: index.php");
} else {
    header("location:javascript://history.go(-1)");
}
?>