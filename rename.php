<?php
require_once "config.php";


$fileName = $_GET["filen"];
$getUser = $_COOKIE["user"];
$newname = '';
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
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
    
        if (!empty(trim($_POST["newname"]))) {
            
            $newname = trim($_POST["newname"]);
            rename($filePath, $fileDir."/".$newname);
            header("location: index.php");
        }
    }   
} else {
    header("location:javascript://history.go(-1)");
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
<body>
    <div class="wrapper">
        <h1>Rename</h1>
        <h2><?php echo '"'.$fileName.'"'; ?></h2>
        <h3>to</h3>
    </div>
	<div class="wrapper">
            <form method="post">
                <div class="form-group">
                    <input type="text" name="newname" class="form-control" value="<?php echo $newname; ?>">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Rename">
                </div>
            </form>
        </div>
</body>
</html>