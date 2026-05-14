<?php
include '../includes/db.php'; // FIXED PATH

$email = "john@test.com";
$username = "john";
$password = password_hash("admin123", PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "sss", $email, $username, $password);
mysqli_stmt_execute($stmt);

echo "User inserted successfully!";
?>