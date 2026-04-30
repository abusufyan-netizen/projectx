<section style="margin-bottom: 3rem;">
    <a href="<?= \Hub\Core\Config::BASE_URL ?>/collaboration" style="color: var(--text-dim); text-decoration: none; display: inline-block; margin-bottom: 2rem;">&larr; Back to Hub</a>
    <h1 class="text-gradient" style="font-size: 2.2rem;">Review Contribution</h1>
    <p style="color: var(--text-dim);">Compare the original work by <strong><?= htmlspecialchars($pr['orig_title']) ?></strong> with the proposed changes from <strong><?= htmlspecialchars($pr['sender_name']) ?></strong>.</p>
</section>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
    <!-- Original -->
    <div class="glass" style="padding: 2rem; border-color: var(--glass-border);">
        <h3 style="color: var(--text-dim); border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem; margin-bottom: 1.5rem;">Original Version</h3>
        <p style="white-space: pre-line; line-height: 1.8; color: var(--text-dim);"><?= htmlspecialchars($pr['orig_desc']) ?></p>
    </div>

    <!-- Proposed -->
    <div class="premium-card" style="border-color: var(--secondary);">
        <h3 style="color: var(--secondary); border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem; margin-bottom: 1.5rem;">Proposed Revision</h3>
        <p style="white-space: pre-line; line-height: 1.8;"><?= htmlspecialchars($pr['fork_desc']) ?></p>
    </div>
</div>

<div class="glass" style="padding: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="color: var(--text-dim);">
        <p>Merging will increment the paper to <strong>v<?= $pr['id'] ?></strong> and add <strong><?= htmlspecialchars($pr['sender_name']) ?></strong> to the contributors list.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <form action="<?= \Hub\Core\Config::BASE_URL ?>/merge/<?= $pr['id'] ?>" method="POST">
            <button type="submit" class="btn btn-primary" onclick="return confirm('Note: This will update the official paper version. Continue?')">Approve & Merge</button>
        </form>
        <button class="btn glass" onclick="history.back()">Reject</button>
    </div>
</div>
