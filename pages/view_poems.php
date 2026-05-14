<?php
// Auth guard MUST come before any HTML output
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?login_error=required");
    exit();
}

include '../includes/header.php';

$user_id = intval($_SESSION['user_id']);
$result  = mysqli_query($conn, "SELECT * FROM poems WHERE user_id = $user_id ORDER BY created_at DESC");
$count   = mysqli_num_rows($result);
?>

<div class="saved-section">
    <div class="saved-header">
        <div class="saved-header-text">
            <h2 class="section-title" style="text-align:left; margin-bottom:6px;">My Saved Poems</h2>
            <p class="section-sub" style="text-align:left; margin-bottom:0;">
                <?php if ($count > 0): ?>
                    You have <strong style="color:#c084fc;"><?php echo $count; ?></strong> poem<?php echo $count !== 1 ? 's' : ''; ?> saved.
                <?php else: ?>
                    Your poetry collection is empty.
                <?php endif; ?>
            </p>
        </div>
        <a href="generate.php" class="btn-primary" style="width:auto; white-space:nowrap;">✨ Generate New</a>
    </div>

    <?php if ($count === 0): ?>
    <!-- EMPTY STATE -->
    <div class="empty-state">
        <div class="empty-icon">📜</div>
        <h3>No poems yet</h3>
        <p>You haven't saved any poems. Generate one and save it to your collection!</p>
        <a href="generate.php" class="btn-primary" style="width:auto; margin-top:20px;">✨ Generate a Poem</a>
    </div>

    <?php else: ?>
    <!-- SEARCH BAR -->
    <div class="search-bar-wrap">
        <div class="input-wrapper" style="max-width:380px;">
            <span class="input-icon">🔍</span>
            <input type="text" id="searchPoems" placeholder="Search by theme..." oninput="filterPoems(this.value)">
        </div>
    </div>

    <!-- POEMS GRID -->
    <div class="poems-grid" id="poemsGrid">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="poem-card" data-theme="<?php echo strtolower(htmlspecialchars($row['theme'])); ?>">
            <div class="poem-card-header">
                <div class="poem-card-meta">
                    <span class="poem-theme-badge">🎯 <?php echo htmlspecialchars($row['theme']); ?></span>
                    <span class="poem-date">📅 <?php echo date("d M Y", strtotime($row['created_at'])); ?></span>
                </div>
                <div class="poem-card-actions">
                    <button class="btn-card-action copy-action" onclick="copyPoem(this)" data-poem="<?php echo htmlspecialchars($row['poem']); ?>" title="Copy">
                        📋
                    </button>
                    <button class="btn-card-action delete-action" onclick="deletePoem(<?php echo $row['id']; ?>, this)" title="Delete">
                        🗑️
                    </button>
                </div>
            </div>
            <div class="poem-card-body">
                <pre class="poem-card-text"><?php echo htmlspecialchars($row['poem']); ?></pre>
            </div>
            <div class="poem-card-footer">
                <button class="btn-expand" onclick="toggleExpand(this)">Show more ↓</button>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <div class="no-results hidden" id="noResults">
        <p>🔍 No poems found matching your search.</p>
    </div>
    <?php endif; ?>
</div>

<div id="toast" class="toast hidden"></div>

<?php include '../includes/footer.php'; ?>

<script>
function copyPoem(btn) {
    const poem = btn.getAttribute('data-poem');
    navigator.clipboard.writeText(poem).then(() => {
        btn.textContent = '✅';
        setTimeout(() => { btn.textContent = '📋'; }, 2000);
        showToast('📋 Poem copied to clipboard!', 'success');
    }).catch(() => {
        const t = document.createElement('textarea');
        t.value = poem;
        document.body.appendChild(t);
        t.select();
        document.execCommand('copy');
        document.body.removeChild(t);
        btn.textContent = '✅';
        setTimeout(() => { btn.textContent = '📋'; }, 2000);
        showToast('📋 Poem copied!', 'success');
    });
}

function deletePoem(id, btn) {
    if (!confirm('Are you sure you want to delete this poem? This cannot be undone.')) return;

    const card = btn.closest('.poem-card');
    card.style.opacity = '0.5';
    card.style.pointerEvents = 'none';

    const formData = new FormData();
    formData.append('id', id);

    fetch('../pages/delete_poem.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                card.style.transition = 'all 0.4s ease';
                card.style.transform = 'scale(0.9)';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.remove();
                    updateCount();
                }, 400);
                showToast('🗑️ Poem deleted.', '');
            } else {
                card.style.opacity = '1';
                card.style.pointerEvents = '';
                showToast('❌ Could not delete poem.', 'error');
            }
        })
        .catch(() => {
            card.style.opacity = '1';
            card.style.pointerEvents = '';
            showToast('❌ Network error.', 'error');
        });
}

function updateCount() {
    const remaining = document.querySelectorAll('.poem-card').length;
    const sub = document.querySelector('.saved-header .section-sub');
    if (sub) {
        if (remaining === 0) {
            location.reload();
        } else {
            sub.innerHTML = `You have <strong style="color:#c084fc;">${remaining}</strong> poem${remaining !== 1 ? 's' : ''} saved.`;
        }
    }
}

function toggleExpand(btn) {
    const body = btn.closest('.poem-card').querySelector('.poem-card-body');
    const isExpanded = body.classList.toggle('expanded');
    btn.textContent = isExpanded ? 'Show less ↑' : 'Show more ↓';
}

function filterPoems(query) {
    const cards = document.querySelectorAll('.poem-card');
    const noResults = document.getElementById('noResults');
    query = query.toLowerCase().trim();
    let visible = 0;

    cards.forEach(card => {
        const theme = card.getAttribute('data-theme') || '';
        const text  = card.querySelector('.poem-card-text')?.textContent.toLowerCase() || '';
        const match = !query || theme.includes(query) || text.includes(query);
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    noResults.classList.toggle('hidden', visible > 0);
}

function showToast(msg, type) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.className = 'toast ' + (type || '');
    toast.classList.remove('hidden');
    clearTimeout(toast._t);
    toast._t = setTimeout(() => toast.classList.add('hidden'), 3800);
}
</script>
