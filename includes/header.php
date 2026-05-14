<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AI Poetry Generator — Create beautiful, personalized poems using Artificial Intelligence. Login or sign up to get started.">
    <title>AI Poetry Generator — Create Beautiful Poems with AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../assets/css/style.css' : 'assets/css/style.css'; ?>">
    <style>
        .forgot-password {
    text-align: right;
    margin-top: 6px;
}

.forgot-password a {
    font-size: 13px;
    color: #6c63ff;
    text-decoration: none;
}

.forgot-password a:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../index.php' : 'index.php'; ?>" class="nav-brand">
            🖊️ <span>AI Poetry</span>
        </a>
        <ul class="nav-links">
            <li class="home-link"><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../index.php' : 'index.php'; ?>">Home</a></li>
            <li class="features-link"><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? 'generate.php' : 'pages/generate.php'; ?>">Generate Poems</a></li>
            <li class="saved-link"><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? 'view_poems.php' : 'pages/view_poems.php'; ?>">Saved Poems</a></li>
            <li class="about-link"><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? 'about.php' : 'pages/about.php'; ?>">About</a></li>
            <li class="contact-link"><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? 'contact.php' : 'pages/contact.php'; ?>">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
                <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? 'logout.php' : 'pages/logout.php'; ?>" class="btn-nav-logout">Logout</a></li>
            <?php else: ?>
                <li><a href="#" onclick="openModal('loginModal')" class="btn-nav-outline" style="color:#a0a0c8;font-size:.9rem;font-weight:500;">Login</a></li>
                <li><a href="#" onclick="openModal('registerModal')" class="btn-nav">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- ===================== Login Modal ===================== -->
<div class="modal-overlay" id="loginModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('loginModal')" aria-label="Close">✕</button>
        <div class="modal-logo">🖊️</div>
        <div class="modal-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your AI Poetry account</p>
        </div>
        <form action="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? 'login.php' : 'pages/login.php'; ?>" method="POST">
            <div class="form-group">
                <label for="loginEmail">Email Address</label>
                <div class="input-wrapper">
                    <span class="input-icon">✉️</span>
                    <input type="email" id="loginEmail" name="loginEmail" placeholder="you@example.com" required>
                </div>
            </div>
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <div class="password-wrapper input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="loginPassword" name="loginPassword" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" onclick="togglePass('loginPassword', this)">Show</button>
                </div>
                <!-- Forgot Password Link -->
                <div class="forgot-password">
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>
            </div>
            <button type="submit" class="btn-primary" id="loginBtn">Sign In →</button>
        </form>
        <p class="modal-switch">Don't have an account? <a href="#" onclick="switchModal('loginModal','registerModal')">Create one →</a></p>
    </div>
</div>

<!-- ===================== Register Modal ===================== -->
<div class="modal-overlay" id="registerModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('registerModal')" aria-label="Close">✕</button>
        <div class="modal-logo">✨</div>
        <div class="modal-header">
            <h2>Create Account</h2>
            <p>Join thousands of AI poetry creators</p>
        </div>
        <form action="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? 'register.php' : 'pages/register.php'; ?>" method="POST">
            <div class="form-group">
                <label for="registerName">Full Name</label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" id="registerName" name="registerName" placeholder="John Doe" required>
                </div>
            </div>
            <div class="form-group">
                <label for="registerEmail">Email Address</label>
                <div class="input-wrapper">
                    <span class="input-icon">✉️</span>
                    <input type="email" id="registerEmail" name="registerEmail" placeholder="you@example.com" required>
                </div>
            </div>
            <div class="form-group">
                <label for="registerPassword">Password</label>
                <div class="password-wrapper input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="registerPassword" name="registerPassword" placeholder="Min. 6 characters" required>
                    <button type="button" class="password-toggle" onclick="togglePass('registerPassword', this)">Show</button>
                </div>
            </div>
            <button type="submit" class="btn-primary" id="registerBtn">Create Account →</button>
        </form>
        <p class="modal-switch">Already have an account? <a href="#" onclick="switchModal('registerModal','loginModal')">Sign in →</a></p>
    </div>
</div>

<div class="container">
