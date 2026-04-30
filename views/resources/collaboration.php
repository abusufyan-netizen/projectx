<section style="margin-bottom: 3rem;">
    <h1 class="text-gradient" style="font-size: 2.5rem;">Collaboration Hub</h1>
    <p style="color: var(--text-dim);">Research proposals and contributions submitted by students for your review.</p>
</section>

<div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
    <?php if(empty($requests)): ?>
        <div class="glass" style="padding: 4rem; text-align: center;">
            <p style="color: var(--text-dim);">No pending contributions at this time.</p>
        </div>
    <?php endif; ?>

    <?php foreach($requests as $req): ?>
        <div class="premium-card" style="display: flex; justify-content: space-between; align-items: center; border-left: 4px solid var(--secondary);">
            <div>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.75rem; background: var(--secondary); color: black; padding: 2px 10px; border-radius: 20px; font-weight: 700;">PROPOSAL</span>
                    <span style="font-size: 0.85rem; color: var(--text-dim);">From: <strong><?= htmlspecialchars($req['sender_name']) ?></strong></span>
                </div>
                <h3 style="margin: 0; font-size: 1.2rem;">Regarding: <?= htmlspecialchars($req['paper_title']) ?></h3>
                <p style="font-size: 0.9rem; color: var(--text-dim); margin-top: 0.5rem;">"<?= htmlspecialchars($req['message']) ?>"</p>
                <div style="font-size: 0.8rem; color: var(--text-dim); margin-top: 8px; opacity: 0.7;">Submitted <?= date('M d, Y', strtotime($req['created_at'])) ?></div>
            </div>
            
            <div>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/review/<?= $req['id'] ?>" class="btn btn-primary">Review Changes</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
