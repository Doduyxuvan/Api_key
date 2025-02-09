<?php
require_once "db_config.php";

// Ambil username dan password dari request
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Cek apakah username dan password sudah diisi
if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Username dan password harus diisi"]);
    exit;
}

// Cek apakah user ada di database
$sql = "SELECT * FROM users WHERE username = ? AND password = SHA2(?, 256)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Buat API Key baru
    $new_api_key = bin2hex(random_bytes(16));

    // Simpan API Key di database
    $update_sql = "UPDATE users SET api_key = ? WHERE username = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $new_api_key, $username);
    $update_stmt->execute();

    echo json_encode(["status" => "success", "message" => "API Key berhasil dibuat", "api_key" => $new_api_key]);
} else {
    echo json_encode(["status" => "error", "message" => "Username atau password salah"]);
}

$stmt->close();
$conn->close();
?>
