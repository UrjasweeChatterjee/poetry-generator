<?php
include '../includes/config.php';
include '../includes/db.php';

// Get POST data
$email = trim($_POST['loginEmail']);
$password = trim($_POST['loginPassword']);

// Validate inputs
if (empty($email) || empty($password)) {
    die("Both email and password are required.");
}

// Fetch user from DB
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Start session and set user data
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Redirect to home page with success message
        header("Location: ../index.php?loggedin=1");
        exit();
    } else {
        // Invalid password
        header("Location: ../index.php?login_error=invalid_password");
        exit();
    }
} else {
    header("Location: ../index.php?login_error=email_not_found");
    exit();
}

$stmt->close();
$conn->close();
?>