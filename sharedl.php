<?php
require_once "config.php";

$token = $_GET["token"];

if($stmt = mysqli_prepare($link, "SELECT path FROM share where token = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $param_token);
    $param_token = $token;
    
    if(mysqli_stmt_execute($stmt)) {
		mysqli_stmt_store_result($stmt);
		
		if(mysqli_stmt_num_rows($stmt) == 1) {
			mysqli_stmt_bind_result($stmt, $path);
			
			if(mysqli_stmt_fetch($stmt)) {
                                $dlPath = $path;
			}
		}
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);

if (!isset($dlPath)) {
    echo 'Invalid Token';
    
} else {
    echo '<h2>Download</h2>';
    echo '<div class="form-group"><form method="post">';
    echo '<h3>'.basename($dlPath).'</h3></br>';
    echo '<input class="btn btn-primary" type="submit" name="dload" id="dload" value="Download">';
    echo '</form></div>';
    echo '<link href="style.css" rel="stylesheet"/>';
    echo '<style>div {margin: 3em;} h2 {margin-left: 1em;} .btn-primary {padding: 10px 20px; font-size: 20px;}</style>';
    
    if (array_key_exists('dload',$_POST)){
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
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo basename($dlPath);?></title>
        <meta name="language" content="de" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1">		
        <meta name="description" content="IronLeo Share Download" />					
    </head>
</html>