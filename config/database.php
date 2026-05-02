<?php

$host = "127.0.0.1"; // FORCE TCP (IMPORTANT)
$user = "root";
$password = "";
$database = "xuband_db";
$port = 3306; // adjust if your my.ini says otherwise

$conn = mysqli_connect($host, $user, $password, $database, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected OK"; // TEMP TEST

?>