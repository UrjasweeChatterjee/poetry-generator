// ============================================
// AI Poetry Generator - script.js
// ============================================

// --- Modal Controls ---
function openModal(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}
function switchModal(closeId, openId) {
    closeModal(closeId);
    setTimeout(function() { openModal(openId); }, 120);
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(function(m) {
            m.classList.remove('active');
        });
        document.body.style.overflow = '';
    }
});

// --- Password Toggle ---
function togglePass(inputId, btn) {
    var input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = 'Hide';
    } else {
        input.type = 'password';
        btn.textContent = 'Show';
    }
}

// --- Toast ---
function showToast(msg, type) {
    var toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.className = 'toast ' + (type || '');
    toast.classList.remove('hidden');
    clearTimeout(toast._hideTimer);
    toast._hideTimer = setTimeout(function() {
        toast.classList.add('hidden');
    }, 3800);
}

// Show toast based on URL param (for index.php redirects)
window.addEventListener('DOMContentLoaded', function() {
    // Ensure toast element exists
    if (!document.getElementById('toast')) {
        var t = document.createElement('div');
        t.id = 'toast';
        t.className = 'toast hidden';
        document.body.appendChild(t);
    }

    // Detect which modal to reopen (for error states)
    var urlParams = new URLSearchParams(window.location.search);
    var modal = urlParams.get('modal'); // 'register' or null (login)

    if (typeof TOAST_TYPE !== 'undefined' && TOAST_TYPE) {
        if (TOAST_TYPE === 'registered') {
            showToast('🎉 Account created! You are now logged in.', 'success');

        } else if (TOAST_TYPE === 'loggedin') {
            showToast('👋 Welcome back!', 'success');

        } else if (TOAST_TYPE === 'loggedout') {
            showToast('You\'ve been logged out.', '');

        } else if (TOAST_TYPE === 'error_exists') {
            showToast('⚠️ That email is already registered. Try logging in.', 'error');
            setTimeout(function() { openModal('registerModal'); }, 200);

        } else if (TOAST_TYPE === 'error_empty') {
            showToast('⚠️ Please fill in all required fields.', 'error');
            var m = modal === 'register' ? 'registerModal' : 'loginModal';
            setTimeout(function() { openModal(m); }, 200);

        } else if (TOAST_TYPE === 'error_short_password') {
            showToast('⚠️ Password must be at least 6 characters.', 'error');
            setTimeout(function() { openModal('registerModal'); }, 200);

        } else if (TOAST_TYPE === 'login_error_invalid') {
            showToast('❌ Incorrect email or password. Please try again.', 'error');
            setTimeout(function() { openModal('loginModal'); }, 200); // Reopen modal!

        } else if (TOAST_TYPE === 'login_error_empty') {
            showToast('⚠️ Please enter your email and password.', 'error');
            setTimeout(function() { openModal('loginModal'); }, 200);

        } else if (TOAST_TYPE === 'login_error_required') {
            showToast('🔒 Please log in to continue.', 'error');
            setTimeout(function() { openModal('loginModal'); }, 200);
        }
    }

    // Add button loading state on modal form submit
    document.querySelectorAll('.modal-box form').forEach(function(form) {
        form.addEventListener('submit', function() {
            var btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Please wait...';
            }
        });
    });
});

