<?php
session_start();
include 'includes/header.php';
?>

<?php
// Show toasts based on URL params
$toast = '';
if (isset($_GET['success']) && $_GET['success'] == 1) $toast = 'registered';
elseif (isset($_GET['loggedin']))    $toast = 'loggedin';
elseif (isset($_GET['loggedout']))   $toast = 'loggedout';
elseif (isset($_GET['error']))       $toast = 'error_' . preg_replace('/[^a-z_]/', '', $_GET['error']);
elseif (isset($_GET['login_error'])) $toast = 'login_error_' . preg_replace('/[^a-z_]/', '', $_GET['login_error']);
?>

<!-- TOAST NOTIFICATION -->
<div id="toast" class="toast hidden"></div>

<!-- HERO SECTION -->
<div class="hero">
    <div class="hero-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="hero-badge">
        <span class="glow-dot"></span>
        AI-Powered Poetry
    </div>

    <h1 class="display-title">
        Whispers of the Cosmos,<br>
        <span>Inked by AI</span>
    </h1>
    <p class="lead">Step into a realm where artificial intelligence breathes life into your deepest thoughts, weaving them into breathtaking verses of poetry.</p>

    <div class="hero-actions">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <button class="btn-primary" onclick="openModal('registerModal')" style="width:auto;">✨ Get Started Free</button>
            <button class="btn-outline" onclick="openModal('loginModal')" style="width:auto;">Sign In</button>
        <?php else: ?>
            <a href="pages/logout.php" class="btn-outline" style="width:auto;">👋 Logout, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
        <?php endif; ?>
    </div>

    <img src="assets/images/hero.png" class="hero-img" alt="Premium Poetry Hero" onerror="this.style.display='none'">
</div>


<!-- FEATURES SECTION -->
<div class="section">
    <h2 class="section-title">Everything You Need</h2>
    <p class="section-sub">One platform to generate, save, and share your poetry.</p>
    <div class="card-grid">
        <div class="card">
            <span class="card-icon">🤖</span>
            <h5>AI Generated</h5>
            <p>Create unique poems in seconds with our advanced AI poetry engine.</p>
        </div>
        <div class="card">
            <span class="card-icon">🎨</span>
            <h5>Custom Styles</h5>
            <p>Choose from romantic, sad, funny, or motivational tones for your poem.</p>
        </div>
        <div class="card">
            <span class="card-icon">💾</span>
            <h5>Save Poems</h5>
            <p>Your poems are saved securely to your personal account.</p>
        </div>
        <div class="card">
            <span class="card-icon">📲</span>
            <h5>Share Easily</h5>
            <p>Copy or share your poems to WhatsApp and other platforms instantly.</p>
        </div>
    </div>
</div>


<!-- HOW IT WORKS -->
<div class="section">
    <h2 class="section-title">How It Works</h2>
    <p class="section-sub">Three simple steps to your perfect poem.</p>
    <div class="card-grid three-col">
        <div class="card">
            <div class="step-number">1</div>
            <h5>Sign Up Free</h5>
            <p>Create your account in seconds. No credit card required.</p>
        </div>
        <div class="card">
            <div class="step-number">2</div>
            <h5>Enter Your Theme</h5>
            <p>Provide a topic like love, life, courage, or nature.</p>
        </div>
        <div class="card">
            <div class="step-number">3</div>
            <h5>Generate & Enjoy</h5>
            <p>AI crafts a beautiful poem instantly. Save or share it.</p>
        </div>
    </div>
</div>


<!-- CTA SECTION -->
<div class="cta-section">
    <h2>Start Creating Poetry Now</h2>
    <p>Join thousands of users who express themselves through AI-powered poetry.</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <button onclick="openModal('registerModal')" class="btn-light">Create Free Account →</button>
    <?php else: ?>
        <p style="color:#c084fc; font-weight:600;">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! You're all set. ✨</p>
    <?php endif; ?>
</div>


<?php include 'includes/footer.php'; ?>

<script>
    // Pass session state and toast type to JS
    const IS_LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    const TOAST_TYPE   = "<?php echo $toast; ?>";
</script>
<script src="assets/js/script.js"></script>