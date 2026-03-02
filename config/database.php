<?php
$hostname = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'ukk_parkir';
$port = getenv('DB_PORT') ?: 3306;

// For cloud databases like Aiven that require SSL
$conn = mysqli_init();
if (getenv('DB_SSL') === 'true' || strpos($hostname, 'aivencloud.com') !== false) {
    // In some environments, you might need to specify the path to a CA cert
    // But often just enabling SSL flag is enough for basic connectivity
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
    mysqli_real_connect($conn, $hostname, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL);
}
else {
    mysqli_real_connect($conn, $hostname, $username, $password, $database, $port);
}

if (!$conn || mysqli_connect_errno()) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
