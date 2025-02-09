<?php
require_once "db_config.php";

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Username dan password harus diisi"]);
    exit;
}

// Cek username & password di database
$sql = "SELECT * FROM users WHERE username = ? AND password = SHA2(?, 256)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(["status" => "success", "message" => "Login berhasil", "api_key" => $user['api_key']]);
} else {
    echo json_encode(["status" => "error", "message" => "Username atau password salah"]);
}

$stmt->close();
$conn->close();
?>
