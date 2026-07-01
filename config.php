<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Use the DB_ variables from Railway
$host = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'marks_system';
$port = getenv('DB_PORT') ?: 3306;

// Create connection
$conn = mysqli_connect($host, $username, $password, $database, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
