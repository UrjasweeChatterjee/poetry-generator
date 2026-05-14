<?php include '../includes/header.php'; ?>

<div class="about-hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="hero-badge">🖊️ Our Story</div>
    <h1 class="display-title">About <span>AI Poetry</span></h1>
    <p class="lead">We believe everyone has a poet inside them. AI Poetry gives that poet a voice.</p>
</div>

<!-- MISSION -->
<div class="section">
    <div class="about-mission">
        <div class="about-mission-text">
            <h2 class="section-title" style="text-align:left;">Our Mission</h2>
            <p style="color:#8080b0; line-height:1.85; margin-bottom:16px;">
                AI Poetry Generator was built with a single belief: poetry should be accessible to everyone.
                Whether you're writing for a loved one, processing emotions, or simply exploring creativity —
                our AI helps you find the perfect words.
            </p>
            <p style="color:#8080b0; line-height:1.85;">
                Using Google's Gemini AI model, we turn your simple theme into beautifully crafted verses —
                romantic, melancholic, motivational, mystical, or anything in between.
            </p>
        </div>
        <div class="about-mission-stats">
            <div class="stat-card">
                <span class="stat-num">8+</span>
                <span class="stat-label">Poetry Styles</span>
            </div>
            <div class="stat-card">
                <span class="stat-num">∞</span>
                <span class="stat-label">Poems Generated</span>
            </div>
            <div class="stat-card">
                <span class="stat-num">🆓</span>
                <span class="stat-label">Always Free</span>
            </div>
        </div>
    </div>
</div>

<!-- FEATURES -->
<div class="section">
    <h2 class="section-title">What Makes Us Special</h2>
    <p class="section-sub">Everything you need to express yourself through poetry.</p>
    <div class="card-grid">
        <div class="card">
            <span class="card-icon">🤖</span>
            <h5>Gemini AI Powered</h5>
            <p>We use Google's state-of-the-art Gemini AI to generate high-quality, creative poetry.</p>
        </div>
        <div class="card">
            <span class="card-icon">🎨</span>
            <h5>8 Unique Styles</h5>
            <p>From romantic to gothic, choose the perfect tone and style for every occasion.</p>
        </div>
        <div class="card">
            <span class="card-icon">💾</span>
            <h5>Personal Collection</h5>
            <p>Save all your favourite poems to your personal library, accessible anytime.</p>
        </div>
        <div class="card">
            <span class="card-icon">🔒</span>
            <h5>Secure & Private</h5>
            <p>Your account and poems are securely stored with hashed passwords and protected queries.</p>
        </div>
        <div class="card">
            <span class="card-icon">📋</span>
            <h5>One-Click Copy</h5>
            <p>Instantly copy any poem to share on WhatsApp, Instagram, or anywhere you like.</p>
        </div>
        <div class="card">
            <span class="card-icon">📱</span>
            <h5>Fully Responsive</h5>
            <p>Works beautifully on desktop, tablet, and mobile devices.</p>
        </div>
    </div>
</div>

<!-- HOW IT WORKS -->
<div class="section">
    <h2 class="section-title">How It Works</h2>
    <p class="section-sub">Three simple steps to your masterpiece.</p>
    <div class="card-grid three-col">
        <div class="card">
            <div class="step-number">1</div>
            <h5>Create an Account</h5>
            <p>Sign up for free in seconds. No credit card, no spam — just creativity.</p>
        </div>
        <div class="card">
            <div class="step-number">2</div>
            <h5>Pick Theme & Style</h5>
            <p>Enter your topic, choose a poetry style, and pick your preferred length.</p>
        </div>
        <div class="card">
            <div class="step-number">3</div>
            <h5>Generate & Save</h5>
            <p>Let AI write your poem in seconds, then save, copy, or share it instantly.</p>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="cta-section">
    <h2>Ready to Create Poetry?</h2>
    <p>Join now and express your emotions through the art of AI-powered poetry.</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <button onclick="openModal('registerModal')" class="btn-light">Create Free Account →</button>
    <?php else: ?>
        <a href="generate.php" class="btn-light" style="text-decoration:none;">✨ Generate a Poem →</a>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
