<section style="margin-bottom: 3rem;">
    <h1 class="text-gradient" style="font-size: 2.5rem;">Recycle Bin</h1>
    <p style="color: var(--text-dim);">Recover your deleted academic resources. Items here are visible only to you.</p>
</section>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
    <?php if(empty($resources)): ?>
        <div class="glass" style="grid-column: 1/-1; padding: 4rem; text-align: center;">
            <p style="color: var(--text-dim);">Your trash is empty.</p>
            <a href="<?= \Hub\Core\Config::BASE_URL ?>/" class="btn btn-primary" style="margin-top: 1.5rem;">Return to Repository</a>
        </div>
    <?php endif; ?>

    <?php foreach($resources as $res): ?>
        <div class="premium-card" style="border-color: #ff4d4d; opacity: 0.8;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <span style="font-size: 0.75rem; background: #333; padding: 4px 10px; border-radius: 20px; font-weight: 600;">
                    <?= $res['category'] ?>
                </span>
                <span style="font-size: 0.8rem; color: #ff4d4d;">Deleted <?= date('M d', strtotime($res['deleted_at'])) ?></span>
            </div>
            <h3 style="margin-bottom: 0.5rem; text-decoration: line-through; opacity: 0.6;"><?= htmlspecialchars($res['title']) ?></h3>
            
            <div style="border-top: 1px solid var(--glass-border); padding-top: 1.5rem; margin-top: 1.5rem;">
                <div style="display: flex; gap: 10px;">
                    <a href="<?= \Hub\Core\Config::BASE_URL ?>/restore/<?= $res['bin_id'] ?>" class="btn btn-primary" style="flex: 1; text-align: center; font-size: 0.85rem;">Restore Resource</a>
                    <button class="btn glass" style="flex: 1; color: #ff4d4d; border-color: #ff4d4d; font-size: 0.85rem; cursor: not-allowed;" title="Permanent purge coming soon">Purge</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
