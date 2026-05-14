<?php
// TEMPORARY ADMIN TOOL — Delete this file after use!
// Access: http://localhost/poetry_generator/reset_password.php

include 'includes/db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'reset') {
        $email    = trim($_POST['email'] ?? '');
        $newpass  = $_POST['new_password'] ?? '';

        if (empty($email) || empty($newpass) || strlen($newpass) < 6) {
            $message = '<span style="color:red">❌ Email and password (min 6 chars) are required.</span>';
        } else {
            $hashed = password_hash($newpass, PASSWORD_DEFAULT);
            $stmt   = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "ss", $hashed, $email);
            mysqli_stmt_execute($stmt);
            $rows = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            $message = $rows > 0
                ? "<span style='color:green'>✅ Password updated for <b>$email</b>. You can now log in.</span>"
                : "<span style='color:red'>❌ No user found with that email.</span>";
        }
    }

    if ($action === 'create') {
        $name    = trim($_POST['new_name'] ?? '');
        $email   = trim($_POST['new_email'] ?? '');
        $pass    = $_POST['new_pass'] ?? '';
        if (empty($name) || empty($email) || strlen($pass) < 6) {
            $message = '<span style="color:red">❌ All fields required (password min 6 chars).</span>';
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt   = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed);
            if (mysqli_stmt_execute($stmt)) {
                $message = "<span style='color:green'>✅ New user <b>$name</b> (<b>$email</b>) created. You can now log in.</span>";
            } else {
                $message = "<span style='color:red'>❌ Error: Email already exists or DB error.</span>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    if ($action === 'delete') {
        $del_id = (int)($_POST['del_id'] ?? 0);
        if ($del_id > 0) {
            mysqli_query($conn, "DELETE FROM users WHERE id = $del_id");
            $message = "<span style='color:green'>✅ User ID $del_id deleted.</span>";
        }
    }
}

$users = mysqli_query($conn, "SELECT id, username, email, created_at FROM users ORDER BY id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Password Reset Tool</title>
<style>
body { font-family: sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background:#f5f5f5; }
h1 { color:#333; } h2 { color:#555; margin-top:30px; }
.box { background:#fff; border-radius:8px; padding:20px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,.1); }
label { display:block; margin:8px 0 4px; font-weight:600; }
input[type=text], input[type=email], input[type=password] { width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box; }
button { margin-top:10px; padding:9px 18px; background:#7c3aed; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:14px; }
button:hover { background:#6d28d9; }
.del-btn { background:#dc2626; }
.del-btn:hover { background:#b91c1c; }
table { width:100%; border-collapse:collapse; }
th, td { padding:8px 12px; text-align:left; border-bottom:1px solid #eee; }
th { background:#f0f0f0; }
.msg { padding:12px; border-radius:6px; background:#e8f5e9; margin-bottom:16px; }
.warn { background:#fff3e0; padding:10px; border-radius:6px; border-left:4px solid #ff9800; margin-bottom:16px; font-size:13px; }
</style>
</head>
<body>
<h1>🔧 Password Reset Tool</h1>
<p class="warn">⚠️ <strong>Security Notice:</strong> Delete this file after use — <code>reset_password.php</code></p>

<?php if ($message): ?>
<div class="msg"><?= $message ?></div>
<?php endif; ?>

<div class="box">
<h2>Existing Users</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th><th>Delete</th></tr>
<?php while ($u = mysqli_fetch_assoc($users)): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['username']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= $u['created_at'] ?></td>
    <td>
        <form method="POST" onsubmit="return confirm('Delete user <?= htmlspecialchars($u['email']) ?>?')">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="del_id" value="<?= $u['id'] ?>">
            <button type="submit" class="del-btn" style="padding:4px 10px;font-size:12px;">Delete</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>

<div class="box">
<h2>Reset an Existing User's Password</h2>
<form method="POST">
    <input type="hidden" name="action" value="reset">
    <label>User Email:</label>
    <input type="email" name="email" placeholder="user@example.com" required>
    <label>New Password (min 6 chars):</label>
    <input type="password" name="new_password" placeholder="New password" required>
    <button type="submit">Reset Password</button>
</form>
</div>

<div class="box">
<h2>Create a Fresh Test Account</h2>
<form method="POST">
    <input type="hidden" name="action" value="create">
    <label>Full Name:</label>
    <input type="text" name="new_name" placeholder="Your Name" required>
    <label>Email:</label>
    <input type="email" name="new_email" placeholder="you@example.com" required>
    <label>Password (min 6 chars):</label>
    <input type="password" name="new_pass" placeholder="Choose a password" required>
    <button type="submit">Create Account</button>
</form>
</div>
</body>
</html>
