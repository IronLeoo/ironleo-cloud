<?php

$previousdir = $_COOKIE["currentdir"];

if ($previousdir != "C:" || $previousdir != "K:" || $previousdir != "root") {
    while (substr($previousdir, -1) != "/") {
        $previousdir = substr_replace($previousdir, "", -1);
    }

    $previousdir = substr_replace($previousdir, "", -1);
    setcookie("currentdir",$previousdir,time()+30*60);
    header("location: index.php");
} else {
    header("location: index.php");
}

?>

