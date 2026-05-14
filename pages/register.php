<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
// Get POST data
$username = trim($_POST['registerName']);
$email = trim($_POST['registerEmail']);
$password = trim($_POST['registerPassword']);

// Check if email already exists
$check_sql = "SELECT id FROM users WHERE email = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "s", $email);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    // Email already exists
    mysqli_stmt_close($check_stmt);
    header("Location: ../index.php?error=email_exists");
    exit();
}
mysqli_stmt_close($check_stmt);

$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

try {
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../index.php?success=1");
    } else {
        header("Location: ../index.php?error=registration_failed");
    }
} catch (mysqli_sql_exception $e) {
    // 1062 is the MySQL error code for Duplicate Entry
    if ($e->getCode() == 1062) {
        header("Location: ../index.php?error=email_exists");
    } else {
        header("Location: ../index.php?error=registration_failed");
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>