</div> <!-- closing .container from header -->

<footer>
    <div class="footer-grid">
        <div class="brand-col">
            <a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../index.php' : 'index.php'; ?>" class="nav-brand">
                🖊️ AI Poetry
            </a>
            <p>Create beautiful poems using the power of Artificial Intelligence. Express yourself — effortlessly.</p>
        </div>
        <div>
            <h5>Quick Links</h5>
            <ul>
                <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../index.php' : 'index.php'; ?>">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? 'logout.php' : 'pages/logout.php'; ?>">Logout</a></li>
                <?php else: ?>
                    <li><a href="#" onclick="openModal('loginModal')">Login</a></li>
                    <li><a href="#" onclick="openModal('registerModal')">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div>
            <h5>Features</h5>
            <ul>
                <li>AI Poem Generator</li>
                <li>Save Poems</li>
                <li>Share Poems</li>
                <li>Custom Styles</li>
            </ul>
        </div>
        <div>
            <h5>Contact</h5>
            <p>support@aipoetry.com</p>
            <p style="margin-top:6px;">📍 India</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p class="footer-copy">&copy; <?php echo date("Y"); ?> AI Poetry Generator. All rights reserved.</p>
        <div class="footer-socials">
            <a href="#">Twitter</a>
            <a href="#">Instagram</a>
            <a href="#">LinkedIn</a>
        </div>
    </div>
</footer>

<script src="<?php echo (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) ? '../assets/js/script.js' : 'assets/js/script.js'; ?>"></script>
</body>
</html>