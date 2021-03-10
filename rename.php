<?php
require_once "config.php";

$filePath = $_GET["currentdir"];
$filename = $_GET["filen"];
$getUser = $_COOKIE["user"];
$newname = '';
$affectedFile = $filePath.'/'.$filename;

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

session_start();

if($count == 1) {
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
    
        if (!empty(trim($_POST["newname"]))) {
            
            $newname = trim($_POST["newname"]);
            rename($affectedFile, $filePath.$newname);
            header("location: /cloud/index.php?currentdir=$filePath");
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
        <h2><?php echo '"'.$filename.'"'; ?></h2>
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