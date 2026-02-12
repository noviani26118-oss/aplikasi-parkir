<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'ukk_parkir';

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>
