<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include '../includes/header.php';

$success = false;
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['contact_name']    ?? '');
    $email   = trim($_POST['contact_email']   ?? '');
    $subject = trim($_POST['contact_subject'] ?? '');
    $message = trim($_POST['contact_message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In a real app you'd send an email here — for now we just confirm.
        $success = true;
    }
}
?>

<div class="section contact-section">
    <div class="contact-header">
        <div class="hero-badge">💌 Get In Touch</div>
        <h2 class="section-title">Contact Us</h2>
        <p class="section-sub">Have a question, idea, or feedback? We'd love to hear from you.</p>
    </div>

    <div class="contact-grid">
        <!-- INFO COLUMN -->
        <div class="contact-info">
            <div class="contact-info-card">
                <span class="contact-info-icon">✉️</span>
                <div>
                    <h5>Email Us</h5>
                    <p>support@aipoetry.com</p>
                </div>
            </div>
            <div class="contact-info-card">
                <span class="contact-info-icon">📍</span>
                <div>
                    <h5>Location</h5>
                    <p>India 🇮🇳</p>
                </div>
            </div>
            <div class="contact-info-card">
                <span class="contact-info-icon">⏰</span>
                <div>
                    <h5>Response Time</h5>
                    <p>Usually within 24 hours</p>
                </div>
            </div>
            <div class="contact-socials">
                <h5 style="color:#c084fc; font-size:.85rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:12px;">Follow Us</h5>
                <div class="contact-social-links">
                    <a href="#" class="social-pill">🐦 Twitter</a>
                    <a href="#" class="social-pill">📸 Instagram</a>
                    <a href="#" class="social-pill">💼 LinkedIn</a>
                </div>
            </div>
        </div>

        <!-- FORM COLUMN -->
        <div class="contact-form-wrap">
            <?php if ($success): ?>
            <div class="contact-success">
                <div style="font-size:3rem; margin-bottom:16px;">🎉</div>
                <h3 style="color:#fff; font-family:'Playfair Display',serif; margin-bottom:10px;">Message Sent!</h3>
                <p style="color:#7070a0; margin-bottom:24px;">Thank you for reaching out. We'll get back to you within 24 hours.</p>
                <a href="contact.php" class="btn-outline" style="width:auto; padding:10px 24px;">Send Another</a>
            </div>
            <?php else: ?>
            <?php if (!empty($error)): ?>
            <div class="form-error-banner">⚠️ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" class="contact-form" id="contactForm">
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="contact_name">Full Name <span class="req">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input type="text" id="contact_name" name="contact_name" placeholder="Your name" required value="<?php echo htmlspecialchars($_POST['contact_name'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group flex-1">
                        <label for="contact_email">Email Address <span class="req">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon">✉️</span>
                            <input type="email" id="contact_email" name="contact_email" placeholder="you@example.com" required value="<?php echo htmlspecialchars($_POST['contact_email'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact_subject">Subject</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📝</span>
                        <input type="text" id="contact_subject" name="contact_subject" placeholder="What is this about?" value="<?php echo htmlspecialchars($_POST['contact_subject'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact_message">Message <span class="req">*</span></label>
                    <textarea id="contact_message" name="contact_message" placeholder="Write your message here..." rows="6" required><?php echo htmlspecialchars($_POST['contact_message'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn-primary" id="contactBtn">
                    <span>Send Message ✉️</span>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<script>
document.getElementById('contactForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('contactBtn');
    if (btn) { btn.disabled = true; btn.querySelector('span').textContent = 'Sending...'; }
});
</script>
<script src="../assets/js/script.js"></script>
