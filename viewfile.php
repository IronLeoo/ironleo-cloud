<?php
require_once "config.php";

$viewFile = $_GET["file"];
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
        $viewDir = $udir;
    } else {
        $viewDir = $dirk.$udir;
    }
} else {
    if ($udir == "C:") {
        $viewDir = $_COOKIE["currentdir"];
    } else {
        $viewDir = $dirk.$_COOKIE["currentdir"];
    }
}

$viewPath = $viewDir."/".$viewFile;

$filelink = "/cache/". random_int(255, 2048).$viewFile;
$xampppath = "C:/xampp/htdocs";
copy($viewPath, $xampppath.$filelink);

if (in_array(strtolower(pathinfo($viewPath, PATHINFO_EXTENSION)), ["png", "jpg", "jpeg", "gif"])) {
    echo '<img style="-webkit-user-select: none;margin: auto;" src="'.$filelink.'">';

} elseif (pathinfo($viewPath, PATHINFO_EXTENSION) == "mp4") {
    echo '<video controls="" autoplay="" name="media"><source type="video/mp4" src="'.$filelink.'"></video>';
    
} elseif (in_array(strtolower(pathinfo($viewPath, PATHINFO_EXTENSION)), ["mp3", "ogg", "m4a", "wav"])) {
    echo '<video controls="" autoplay="" name="media"><source src="'.$filelink.'" type="audio/mpeg"></video>';
    
} elseif (in_array(strtolower(pathinfo($viewPath, PATHINFO_EXTENSION)), ["txt", "log"])) {
    echo '<body style="background-color: #101010; color: #ffffff; justify-content: unset;"><p>'. str_replace("\n","<br />", file_get_contents($xampppath.$filelink)) .'</p></body>';

} else {
    echo '<p style="color: #EEEEEE">This file cannot be viewed.</p>';
}
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $viewFile ?></title>
        <meta name="language" content="de" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="IronLeo viewing <?php echo $viewFile ?>" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 350px; padding: 20px; }
            video:-webkit-full-page-media {
                position: absolute;
                top: 0px;
                right: 0px;
                bottom: 0px;
                left: 0px;
                max-height: 100%;
                max-width: 100%;
                margin: auto;
            }
            body:-webkit-full-page-media {
                background-color: rgb(0, 0, 0);
            }
            body {
                background: #0e0e0e;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            video {
                max-height: 90vh;
                max-width: 100vw;
            }
            img {
                max-height: 90vh;
            }
        </style>
    </head>
</html>

