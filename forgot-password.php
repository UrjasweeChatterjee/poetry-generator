<?php
session_start();
include 'includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$message = "";

/* ================= SEND OTP ================= */
if(isset($_POST['send_otp'])) {

    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(email)=LOWER(?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {

        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['step'] = 2;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->SMTPAuth = false;
            $mail->Port = 1025;

            $mail->setFrom('test@test.com', 'AI Poetry');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "<h3>Your OTP is: <b>$otp</b></h3>";

            $mail->send();

            $message = "✅ OTP sent! Check Mailpit (localhost:8025)";
        } catch (Exception $e) {
            $message = "❌ Mail error: {$mail->ErrorInfo}";
        }

    } else {
        $message = "❌ Email not found!";
    }
}

/* ================= VERIFY OTP ================= */
if(isset($_POST['verify_otp'])) {
    $enteredOtp = $_POST['otp'];

    if($enteredOtp == $_SESSION['otp']) {
        $_SESSION['step'] = 3;
        $message = "✅ OTP verified!";
    } else {
        $message = "❌ Invalid OTP!";
    }
}

/* ================= RESET PASSWORD ================= */
if(isset($_POST['reset_password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_SESSION['email'];

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $password, $email);
    $stmt->execute();

    session_destroy();
    $message = "✅ Password reset successful!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - AI Poetry</title>

    <!-- Main Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body style="display:flex; align-items:center; justify-content:center; min-height:100vh; background-image: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(123,47,247,0.15) 0%, transparent 70%);">

<div class="container" style="max-width: 480px; width: 100%;">
    <div class="card generate-card" style="margin: 0;">

        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #fff; text-align: center; margin-bottom: 24px;">
            <i class="fa-solid fa-lock" style="color: #c084fc; font-size: 1.5rem; margin-right: 8px; vertical-align: middle;"></i> Forgot Password
        </h3>

        <?php if($message): ?>
            <div class="info-msg" style="text-align: center;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php
        $step = $_SESSION['step'] ?? 1;

        /* STEP 1 */
        if($step == 1):
        ?>
        <form method="POST" class="generate-form">
            <div class="form-group" style="margin-bottom: 24px;">
                <label>Email Address <span class="req">*</span></label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                </div>
            </div>

            <button type="submit" class="btn-primary" name="send_otp">
                <i class="fa-solid fa-paper-plane"></i> Send OTP
            </button>
            
            <div style="text-align: center; margin-top: 24px; font-size: 0.9rem;">
                <a href="login.php" style="color: #a855f7; transition: color 0.2s;"><i class="fa-solid fa-arrow-left"></i> Back to Login</a>
            </div>
        </form>

        <?php elseif($step == 2): ?>

        <!-- STEP 2 -->
        <p style="text-align: center; color: #a0a0c0; font-size: 0.9rem; margin-bottom: 24px;">
            Enter the 6-digit OTP sent to your email address.
        </p>
        <form method="POST" class="generate-form">
            <div class="form-group" style="margin-bottom: 24px;">
                <label>Enter OTP <span class="req">*</span></label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-shield-halved input-icon"></i>
                    <input type="text" name="otp" placeholder="123456" required>
                </div>
            </div>

            <button type="submit" class="btn-primary" name="verify_otp" style="background: linear-gradient(135deg, #10b981, #34d399); box-shadow: 0 4px 16px rgba(16,185,129,0.35);">
                <i class="fa-solid fa-check"></i> Verify OTP
            </button>
            
            <div style="text-align: center; margin-top: 24px; font-size: 0.9rem;">
                <a href="forgot-password.php" style="color: #a855f7;"><i class="fa-solid fa-rotate-right"></i> Start Over</a>
            </div>
        </form>

        <?php elseif($step == 3): ?>

        <!-- STEP 3 -->
        <form method="POST" class="generate-form">
            <div class="form-group" style="margin-bottom: 24px;">
                <label>New Password <span class="req">*</span></label>
                <div class="input-wrapper password-wrapper">
                    <i class="fa-solid fa-key input-icon"></i>
                    <input type="password" name="password" id="new_password" placeholder="Enter your new password" required>
                </div>
            </div>

            <button type="submit" class="btn-primary" name="reset_password">
                <i class="fa-solid fa-floppy-disk"></i> Reset Password
            </button>
            
            <div style="text-align: center; margin-top: 24px; font-size: 0.9rem;">
                <a href="login.php" style="color: #a855f7;"><i class="fa-solid fa-arrow-left"></i> Back to Login</a>
            </div>
        </form>

        <?php endif; ?>

    </div>
</div>

</body>
</html>