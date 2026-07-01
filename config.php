<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get database credentials from Railway environment variables
$host = getenv('MYSQLHOST') ?: 'localhost';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$database = getenv('MYSQLDATABASE') ?: 'marks_system';
$port = getenv('MYSQLPORT') ?: 3306;

// Create connection
$conn = mysqli_connect($host, $username, $password, $database, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
