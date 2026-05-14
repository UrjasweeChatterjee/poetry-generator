<?php
session_start();
include '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'invalid_method']);
    exit();
}

$id      = intval($_POST['id'] ?? 0);
$user_id = intval($_SESSION['user_id']);

if ($id <= 0) {
    echo json_encode(['error' => 'invalid_id']);
    exit();
}

// Make sure the poem belongs to this user
$stmt = mysqli_prepare($conn, "DELETE FROM poems WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'not_found_or_unauthorized']);
}

mysqli_stmt_close($stmt);
?>
