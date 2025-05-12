<?php


$hostName = "localHost";
$dbUser = "root";
$dbPassword = "";
$dbName = "pctikler";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    # code...
    die("Something is not right");
}


?>