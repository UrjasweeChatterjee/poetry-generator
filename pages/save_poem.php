<?php
session_start();
include '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$theme   = isset($_POST['theme']) ? mysqli_real_escape_string($conn, trim($_POST['theme'])) : '';
$poem    = isset($_POST['poem'])  ? mysqli_real_escape_string($conn, trim($_POST['poem']))  : '';

if (empty($theme) || empty($poem)) {
    echo json_encode(['error' => 'empty_data']);
    exit();
}

$stmt = mysqli_prepare($conn, "INSERT INTO poems (user_id, theme, poem) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "iss", $user_id, $theme, $poem);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'db_error']);
}
mysqli_stmt_close($stmt);
?>
