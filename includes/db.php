<?php
// LIVE SERVER CREDENTIALS
$host = "localhost";
$user = "log2_logtwo";
$pass = "log2@";
$dbname = "log2_logtwo";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Set timezone to Manila
date_default_timezone_set('Asia/Manila');
$conn->query("SET time_zone = '+08:00'");
?>