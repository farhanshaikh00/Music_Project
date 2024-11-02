<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "music";

$con = mysqli_connect($servername,$username,$password,$database) ;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>