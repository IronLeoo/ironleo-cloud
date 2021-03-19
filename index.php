<?php

session_start();
$dirk = "K:";
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || (!isset($_COOKIE["user"]))){
    $_SESSION["loggedin"] = false;
    header("location: login.php");
    exit;
}

if (isset($_COOKIE["currentdir"])) {
    $currentdir = $_COOKIE["currentdir"];
} else {
    $currentdir = "root";
    setcookie("currentdir",$currentdir,time()+7*24*60*60);
}

$user = $_COOKIE["user"];
$uid = '';

require_once "config.php";

$sql = "SELECT id, dir FROM users WHERE password = ?";

if($stmt = mysqli_prepare($link, $sql)) {
	mysqli_stmt_bind_param($stmt, "s", $param_password);
	$param_password = $user;
	
	if(mysqli_stmt_execute($stmt)) {
		mysqli_stmt_store_result($stmt);
		
		if(mysqli_stmt_num_rows($stmt) == 1) {
			mysqli_stmt_bind_result($stmt, $id, $userdir);
			
			if(mysqli_stmt_fetch($stmt)) {
				$_SESSION["id"] = $id;
				$uid = $id;
                                $udir = $userdir;
			}
		}
	}
	mysqli_stmt_close($stmt);
}
mysqli_close($link);

$thelist = '';
$thelist2 = '';
$thelist3 = '';
$thelist4 = '';
$dirs = "K:/share";

if ($user != '') {
    
    function cloudBuildDir($currentdir) {
        $thelist = '';
        if ($handle = opendir($currentdir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && fnmatch("*.*", $file) == false) {
                    $thelist .= '<li><a class="deletebutton" href="deletedir.php?file='.$file.'" onclick="return  confirm(\'Do you want to delete '.$file.'?\')"></a>  <a class="renamebutton" href="rename.php?filen='.$file.'"></a> <a class="sharebutton" href="share.php?filen='.$file.'"></a> <span class="tab"><span class="tab"><a class="filelink" href="openfolder.php?file='.$file.'">'.$file.'</a></li>';
                }
            }
        closedir($handle);
        }
        return $thelist;
    }
        
    function cloudBuildFile ($currentdir) {
        $thelist2 = '';
        $sizeconvert = true;
        if ($handle = opendir($currentdir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && fnmatch("*.*", $file) == true) {
                    $blacklist = array('$Recycle.Bin', 'BOOTSECT.BAK', 'DumpStack.log.tmp', 'hiberfil.sys', 'nginx-1.19.7', 'pagefile.sys', 'swapfile.sys');
                    $fileSize = '';
                    if (!in_array($file, $blacklist)) { 
                        $fileSizeRaw = filesize($currentdir.'/'.$file);
                        $fileSize = byteConvert($fileSizeRaw);
                    }
                    $thelist2 .= '<li><a class="deletebutton" href="delete.php?file='.$file.'" onclick="return  confirm(\'Do you want to delete '.$file.'?\')"></a> <a class="renamebutton" href="rename.php?filen='.$file.'"></a> <a class="sharebutton" href="share.php?filen='.$file.'"></a> <a class="dlbutton" href="download.php?file='.$file.'"></a><span class="tab"><a class="filelink" href="viewfile.php?file='.$file.'" target="_blank">'.$file.'</a><span class="tab"><p class="filesize">'.$fileSize.'</p></li>';
                }
            }
        closedir($handle);
        }
        $sizeconvert = false;
        return $thelist2;
    }
    function byteConvert($size) {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   

        return round(pow(1024, $base - floor($base)), 2) .' '. $suffixes[floor($base)];
    } 
}

if($uid == 1) {
    
    if($currentdir == "root") {
        $currentdir = "C:";
        
        $thelist = cloudBuildDir($currentdir);
        $thelist2 = cloudBuildFile($currentdir);
        
        $currentdir2 = "K:";
        if ($handle = opendir($currentdir2)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && fnmatch("*.*", $file) == false) {
                    $thelist3 .= '<li><a class="deletebutton" href="deletedir.php?file='.$file.'&admin=k" onclick="return  confirm(\'Do you want to delete '.$file.'?\')"></a>  <a class="renamebutton" href="rename.php?filen='.$file.'&admin=k"></a> <a class="sharebutton" href="share.php?filen='.$file.'"></a> <span class="tab"><span class="tab"><a class="filelink" href="openfolder.php?file='.$file.'&admin=k">'.$file.'</a></li>';
                } elseif ($file != "." && $file != ".." && fnmatch("*.*", $file) == true) {
                    $thelist4 .= '<li><a class="deletebutton" href="delete.php?file='.$file.'&admin=k" onclick="return  confirm(\'Do you want to delete '.$file.'?\')"></a> <a class="renamebutton" href="rename.php?filen='.$file.'&admin=k"></a> <a class="sharebutton" href="share.php?filen='.$file.'"></a> <a class="dlbutton" href="download.php?file='.$file.'&admin=k"></a><span class="tab"><a class="filelink" href="viewfile.php?file='.$file.'" target="_blank">'.$file.'</a></li>';
                }
            }
        closedir($handle);
        }
	
    } elseif($currentdir != "root") {
    
	$thelist = cloudBuildDir($currentdir);
        $thelist2 = cloudBuildFile($currentdir);
    }

	
} elseif($uid != 1) {
    
    if($currentdir == "root") {
        $currentdir = $udir;
        $currentdirwok = $dirk.$udir;
        $thelist = cloudBuildDir($currentdirwok);
        $thelist2 = cloudBuildFile($currentdirwok);
	
    } elseif($currentdir != "root") {
        $currentdirwok = $dirk.$currentdir;
	$thelist = cloudBuildDir($currentdirwok);
        $thelist2 = cloudBuildFile($currentdirwok);
    }

}

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>IronLeo Cloud</title>
        <meta name="language" content="de" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1">		
        <meta name="description" content="IronLeo Cloud Main" />					
        <link href="style.css" rel="stylesheet"/>
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 350px; padding: 20px; }
        </style>
    </head>
    <body>
        <div class="sidebar"></div>
        <div class="toolbar">
            <div class="form-group">
                <?php
                    echo '<a class="btn btn-primary" href="previous.php">Back</a> ';
                    echo '<a class="btn btn-primary" onclick="window.location.reload()">Refresh</a> ';
                    echo '<a class="btn btn-primary" href="createdir.php">Create Folder</a> ';
                    echo '<a class="btn btn-primary" href="upload.php">Upload</a>';
                    echo '<span class="tab"><a class="btn btn-primaryyellow" href="root.php">Root</a>';
                    echo '<a class="btn btn-primaryred" href="logout.php">Logout</a>';
                ?>
            </div>
        </div>
        <div class="listhead">
            <h1>List of files:</h1>
            <h3><?php echo $currentdir; ?></h3>
        </div>
        <div class="filelist">
            <ul><?php if (isset($thelist)) {echo $thelist;} ?></ul>
            <ul><?php if (isset($thelist2)) {echo $thelist2;} ?></ul>
        <div class="listhead2">
            <h3><?php if (isset($currentdir2)) {echo $currentdir2;} ?></h3>
        </div>
        <div class="filelist2">
            <ul><?php if (isset($thelist3)) {echo $thelist3;} ?></ul>
            <ul><?php if (isset($thelist4)) {echo $thelist4;} ?></ul>
        </div>
    </body>
</html>