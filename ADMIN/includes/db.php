<?php
$host = "localhost";
$user = "log2_logtwo";
$pass = "log2@";
$dbname = "log2_logtwo";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>