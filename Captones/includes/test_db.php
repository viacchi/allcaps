<?php
include_once __DIR__ . '/../includes/functions.php';

if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Connection failed!";
}
?>
