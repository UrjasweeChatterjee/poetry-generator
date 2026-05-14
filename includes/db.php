<?php
// DB credentials come from env vars.
// On Railway: set in the Railway dashboard Variables tab.
// Locally: loaded from .env file via config.php's load_env().
// config.php must be included before db.php for load_env() to run first.
require_once __DIR__ . '/config.php';

$servername = getenv('DB_HOST')     ?: 'localhost';
$username   = getenv('DB_USER')     ?: 'root';
$password   = getenv('DB_PASSWORD') ?: '';
$dbname     = getenv('DB_NAME')     ?: 'poem';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>