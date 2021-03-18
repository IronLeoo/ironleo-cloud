<?php
require_once "config.php";

$shareFile = $_GET["filen"];
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
                                $count = 1;
			}
		}
    }
    mysqli_stmt_close($stmt);
}

if ($_COOKIE["currentdir"] == "root") {
    $shareDir = $udir;
} else {
    $shareDir = $_COOKIE["currentdir"];
}

$sharePath = $shareDir."/".$shareFile;
$token = hash('md5', $sharePath);

if($count == 1) {
    if($stmt = mysqli_prepare($link, "SELECT count(1) FROM share WHERE token = ?")) {
    mysqli_stmt_bind_param($stmt, "s", $param_token);
    $param_token = $token;
    
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $found);
                if(mysqli_stmt_fetch($stmt)) {
                    $alreadyExist = $found;
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    
    if ($alreadyExist == 0) {
        if($stmt = mysqli_prepare($link, "INSERT INTO share (token, path) VALUES (?, ?)")) {
            mysqli_stmt_bind_param($stmt, "ss", $param_token, $param_path);
            $param_token = $token;
            $param_path = $sharePath;
			
            if(mysqli_stmt_execute($stmt)) {
                $shareURL = 'https://ironleo.de/cloud/sharedl.php?token='.$token.'';
                echo '<h2>Your Share URL:</h2>';
                echo '<span class="tab"><textarea readonly id="shareURL">'.$shareURL.'</textarea><br>';
                echo '<br><span class="tab"><button class="btn btn-primary" id="copyurl">Copy</button>';
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
    
    } else {
        $shareURL = 'https://ironleo.de/cloud/sharedl.php?token='.$token.'';
        echo '<h2>Your Share URL:</h2>';
        echo '<span class="tab"><textarea readonly id="shareURL">'.$shareURL.'</textarea><br>';
        echo '<br><span class="tab"><button class="btn btn-primary" id="copyurl">Copy</button>';
    }
    
} else {
    header("location: index.php");
}
?>
<style>
    textarea {
        caret-color: red;  
	width: 50em;
	height: 3em;
	border: 1px solid #cccccc;
	padding: 0.5em;
	font-family: Tahoma, sans-serif;
    }
</style>
<link href="style.css" rel="stylesheet"/>
<script type="text/javascript">
    copyurl.onclick = el =>{
    navigator.clipboard.writeText(shareURL.value)
    }
</script>
