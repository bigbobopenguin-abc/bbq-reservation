<?php

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME');
$port = (int)getenv('DB_PORT');

$mysqli = mysqli_init();

$mysqli->ssl_set(
    null,
    null,
    null,
    null,
    null
);

$mysqli->real_connect(
    $host,
    $user,
    $password,
    $database,
    $port,
    null,
    MYSQLI_CLIENT_SSL
);

if ($mysqli->connect_error) {
    http_response_code(500);
    die('Database connection failed: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');

?>
