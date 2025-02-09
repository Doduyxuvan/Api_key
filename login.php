<?php
header("Content-Type: application/json");

// Sertakan file koneksi database
require_once "db_config.php";

// Ambil API key dari request
$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : '';

if (empty($api_key)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "API Key diperlukan"]);
    exit;
}

// Periksa API Key di database
$sql = "SELECT * FROM users WHERE api_key = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $api_key);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        "status" => "success",
        "message" => "Login berhasil",
        "username" => $user['username']
    ]);
} else {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Akses ditolak, API Key salah"]);
}

$stmt->close();
$conn->close();
?>
