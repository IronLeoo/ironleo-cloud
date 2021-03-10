<?php
require_once "config.php";

$destDir = $_GET["currentdir"];
$destDirfull = $destDir.'/';
$getUser = $_COOKIE["user"];
$uploadfile = '';

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
    if(isset($_FILES['userfile']['name'])) {
        $uploadfile = $destDirfull . basename($_FILES['userfile']['name']);
        echo '<pre>';
        
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            header("location: /cloud/index.php?currentdir=$destDir");
        print "</pre>";
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
        <title>Upload</title>
        <meta name="language" content="de" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1">		
        <meta name="description" content="IronLeo File Upload" />					
        <link href="style.css" rel="stylesheet"/>
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 350px; padding: 20px; }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <h1>Upload file</h1>
        </div>
	<div class="wrapper">
            <form enctype="multipart/form-data" action="upload.php?currentdir=<?php echo $destDir; ?>" method="POST">
                <div class="form-group">
                    <input type="hidden" name="MAX_FILE_SIZE" value="5000000000" />
                    <input type="file" name="userfile" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Upload">
                </div>
            </form>
        </div>
    </body>
</html>