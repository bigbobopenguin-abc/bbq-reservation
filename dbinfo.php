<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'bbq_reservation';
$port = (int)(getenv('DB_PORT') ?: 3306);

$mysqli = new mysqli($host, $user, $password, $database, $port);

if ($mysqli->connect_error) {
    http_response_code(500);
    die('Database connection failed. Check the DB_* environment variables.');
}

$mysqli->set_charset('utf8mb4');
?>
