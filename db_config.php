<?php
$host = "localhost"; // Ganti sesuai kebutuhan
$user = "root";
$pass = "";
$db = "api_login";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
