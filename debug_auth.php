<?php
// Temporary diagnostic script - DELETE AFTER DEBUGGING
include 'includes/db.php';

echo "<h2>Auth Diagnostic</h2>";

// Test 1: Check all users
$result = mysqli_query($conn, "SELECT id, username, email, password, LENGTH(password) as pwd_len FROM users");
echo "<h3>Users Table:</h3><pre>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID: {$row['id']} | Username: {$row['username']} | Email: {$row['email']} | PwdLen: {$row['pwd_len']}\n";
    echo "Hash starts with: " . substr($row['password'], 0, 7) . "\n\n";
}
echo "</pre>";

// Test 2: Manually test password_verify
echo "<h3>Test password_verify:</h3><pre>";
$test_passwords = ['test', 'john', '123456', 'password', 'test123'];
$result2 = mysqli_query($conn, "SELECT id, email, password FROM users");
while ($row = mysqli_fetch_assoc($result2)) {
    echo "--- User: {$row['email']} ---\n";
    foreach ($test_passwords as $tp) {
        $match = password_verify($tp, $row['password']);
        if ($match) echo "  ✅ Password '$tp' MATCHES!\n";
    }
}
echo "</pre>";

// Test 3: Check if password_hash works correctly right now
echo "<h3>PHP password_hash test:</h3><pre>";
$test = password_hash('test123', PASSWORD_DEFAULT);
echo "Freshly hashed: $test\n";
echo "Verify works: " . (password_verify('test123', $test) ? 'YES ✅' : 'NO ❌') . "\n";
echo "PHP version: " . phpversion() . "\n";
echo "</pre>";
?>
