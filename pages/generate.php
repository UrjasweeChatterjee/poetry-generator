<?php
session_start();
include '../includes/config.php';
include '../includes/header.php';
?>

<div class="section generate-section">
    <div class="generate-header">
        <div class="generate-badge">✨ AI-Powered</div>
        <h2 class="section-title">Generate Your Poem</h2>
        <p class="section-sub">Describe a theme, pick a style, and watch AI craft poetry just for you.</p>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
    <div class="info-msg">
        🔒 <a href="#" onclick="openModal('loginModal')">Login</a> or <a href="#" onclick="openModal('registerModal')">Register</a> to save your generated poems.
    </div>
    <?php endif; ?>

    <!-- GENERATE FORM -->
    <div class="generate-card">
        <form id="generateForm" class="generate-form">
            <div class="form-row">
                <div class="form-group flex-1">
                    <label for="theme">Poem Theme</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🎯</span>
                        <input type="text" id="theme" name="theme" placeholder="e.g. love, courage, the ocean at night..." autocomplete="off">
                    </div>
                </div>
                <div class="form-group" style="min-width:180px;">
                    <label for="style">Style</label>
                    <div class="input-wrapper select-wrapper">
                        <span class="input-icon">🎨</span>
                        <select id="style" name="style">
                            <option value="beautiful and emotional">✨ Emotional</option>
                            <option value="romantic and passionate">❤️ Romantic</option>
                            <option value="sad and melancholic">🌧️ Melancholic</option>
                            <option value="uplifting and motivational">🔥 Motivational</option>
                            <option value="funny and humorous">😄 Humorous</option>
                            <option value="mystical and dreamy">🌙 Mystical</option>
                            <option value="haiku style">🌸 Haiku</option>
                            <option value="dark and gothic">🖤 Gothic</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group" style="min-width:180px;">
                    <label for="length">Length</label>
                    <div class="input-wrapper select-wrapper">
                        <span class="input-icon">📏</span>
                        <select id="length" name="length">
                            <option value="short (4-6 lines)">Short (4–6 lines)</option>
                            <option value="medium (8-12 lines)" selected>Medium (8–12 lines)</option>
                            <option value="long (16-20 lines)">Long (16–20 lines)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group flex-1" style="display:flex; align-items:flex-end;">
                    <button type="submit" class="btn-primary" id="generateBtn" style="margin-top:0;">
                        <span id="btnText">✨ Generate Poem</span>
                        <span id="btnLoader" class="btn-spinner hidden"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- LOADING STATE -->
    <div id="loadingState" class="loading-state hidden">
        <div class="loading-orbs">
            <div class="l-orb"></div>
            <div class="l-orb"></div>
            <div class="l-orb"></div>
        </div>
        <p class="loading-text">AI is crafting your poem<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span></p>
        <p class="loading-sub">This usually takes a few seconds</p>
    </div>

    <!-- ERROR STATE -->
    <div id="errorState" class="error-state hidden">
        <span class="error-icon">⚠️</span>
        <p id="errorMsg">Something went wrong. Please try again.</p>
        <button class="btn-outline" onclick="resetForm()" style="margin-top:16px; width:auto; padding:9px 22px;">Try Again</button>
    </div>

    <!-- POEM RESULT -->
    <div id="poemResult" class="poem-result-container hidden">
        <div class="poem-result-header">
            <div class="poem-result-meta">
                <span class="poem-theme-tag" id="poemThemeTag"></span>
                <span class="poem-style-tag" id="poemStyleTag"></span>
            </div>
            <div class="poem-result-actions">
                <button class="btn-icon-action" id="copyBtn" onclick="copyGeneratedPoem()" title="Copy poem">
                    📋 Copy
                </button>
                <?php if (isset($_SESSION['user_id'])): ?>
                <button class="btn-icon-action save-btn" id="saveBtn" onclick="saveGeneratedPoem()">
                    💾 Save
                </button>
                <?php else: ?>
                <button class="btn-icon-action save-btn" onclick="openModal('loginModal')" title="Login to save">
                    🔒 Login to Save
                </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="poem-display">
            <div class="poem-decoration">❝</div>
            <pre id="poemText" class="poem-text-display"></pre>
        </div>
        <div class="poem-result-footer">
            <button class="btn-outline" onclick="resetForm()" style="width:auto; padding:10px 24px; font-size:.9rem;">
                🔄 Generate Another
            </button>
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="view_poems.php" class="btn-outline" style="width:auto; padding:10px 24px; font-size:.9rem;">
                📚 View My Poems
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
const IS_LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
let currentPoem = '';
let currentTheme = '';

document.getElementById('generateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const theme = document.getElementById('theme').value.trim();
    const style = document.getElementById('style').value;
    const length = document.getElementById('length').value;

    if (!theme) {
        showInputError('Please enter a theme for your poem.');
        return;
    }

    currentTheme = theme;

    // UI States
    setLoading(true);
    document.getElementById('poemResult').classList.add('hidden');
    document.getElementById('errorState').classList.add('hidden');

    try {
        const formData = new FormData();
        formData.append('theme', theme);
        formData.append('style', style);
        formData.append('length', length);

        const resp = await fetch('../pages/generate_api.php', {
            method: 'POST',
            body: formData
        });
        const data = await resp.json();

        if (data.error) {
            showError(data.error);
        } else {
            currentPoem = data.poem;
            displayPoem(data.poem, theme, style);
        }
    } catch (err) {
        showError('Network error. Please check your connection and try again.');
    } finally {
        setLoading(false);
    }
});

function setLoading(loading) {
    const btn = document.getElementById('generateBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    const loadingState = document.getElementById('loadingState');

    btn.disabled = loading;
    if (loading) {
        btnText.textContent = 'Generating...';
        btnLoader.classList.remove('hidden');
        loadingState.classList.remove('hidden');
        loadingState.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
        btnText.textContent = '✨ Generate Poem';
        btnLoader.classList.add('hidden');
        loadingState.classList.add('hidden');
    }
}

function displayPoem(poem, theme, style) {
    document.getElementById('poemText').textContent = poem;
    document.getElementById('poemThemeTag').textContent = '🎯 ' + theme;

    const styleLabels = {
        'beautiful and emotional': '✨ Emotional',
        'romantic and passionate': '❤️ Romantic',
        'sad and melancholic': '🌧️ Melancholic',
        'uplifting and motivational': '🔥 Motivational',
        'funny and humorous': '😄 Humorous',
        'mystical and dreamy': '🌙 Mystical',
        'haiku style': '🌸 Haiku',
        'dark and gothic': '🖤 Gothic'
    };
    document.getElementById('poemStyleTag').textContent = styleLabels[style] || style;

    document.getElementById('saveBtn') && (document.getElementById('saveBtn').disabled = false);
    document.getElementById('saveBtn') && (document.getElementById('saveBtn').textContent = '💾 Save');

    const container = document.getElementById('poemResult');
    container.classList.remove('hidden');
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function showError(msg) {
    document.getElementById('errorMsg').textContent = msg;
    document.getElementById('errorState').classList.remove('hidden');
    document.getElementById('errorState').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function showInputError(msg) {
    const input = document.getElementById('theme');
    input.style.borderColor = '#ef4444';
    input.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.15)';
    setTimeout(() => { input.style.borderColor = ''; input.style.boxShadow = ''; }, 2500);

    // Show brief error toast
    showToast('⚠️ ' + msg, 'error');
}

function resetForm() {
    document.getElementById('poemResult').classList.add('hidden');
    document.getElementById('errorState').classList.add('hidden');
    document.getElementById('theme').value = '';
    document.getElementById('theme').focus();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function copyGeneratedPoem() {
    if (!currentPoem) return;
    navigator.clipboard.writeText(currentPoem).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.textContent = '✅ Copied!';
        setTimeout(() => { btn.textContent = '📋 Copy'; }, 2000);
        showToast('📋 Poem copied to clipboard!', 'success');
    }).catch(() => {
        // Fallback
        const textarea = document.createElement('textarea');
        textarea.value = currentPoem;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('📋 Poem copied!', 'success');
    });
}

async function saveGeneratedPoem() {
    if (!currentPoem || !IS_LOGGED_IN) return;
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Saving...';

    try {
        const formData = new FormData();
        formData.append('theme', currentTheme);
        formData.append('poem', currentPoem);

        const resp = await fetch('../pages/save_poem.php', { method: 'POST', body: formData });
        const data = await resp.json();

        if (data.success) {
            btn.textContent = '✅ Saved!';
            showToast('💾 Poem saved to your collection!', 'success');
        } else {
            btn.textContent = '💾 Save';
            btn.disabled = false;
            showToast('❌ Could not save poem.', 'error');
        }
    } catch (err) {
        btn.textContent = '💾 Save';
        btn.disabled = false;
        showToast('❌ Network error.', 'error');
    }
}

// toast function (if not already defined)
if (typeof showToast === 'undefined') {
    function showToast(msg, type) {
        let toast = document.getElementById('toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast';
            document.body.appendChild(toast);
        }
        toast.textContent = msg;
        toast.className = 'toast ' + (type || '');
        toast.classList.remove('hidden');
        clearTimeout(toast._t);
        toast._t = setTimeout(() => toast.classList.add('hidden'), 3800);
    }
}
</script>