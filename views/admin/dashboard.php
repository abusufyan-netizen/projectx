<section style="margin-bottom: 3rem;">
    <h1 class="text-gradient" style="font-size: 2.5rem;">University Overview</h1>
    <p style="color: var(--text-dim);">Centralized control for all departments, faculty, and academic resources.</p>
</section>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div class="premium-card" style="text-align: center; padding: 2rem;">
        <h4 style="color: var(--text-dim); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Members</h4>
        <div style="font-size: 2.5rem; font-weight: 800; color: var(--secondary);"><?= $stats['total_users'] ?></div>
    </div>
    <div class="premium-card" style="text-align: center; padding: 2rem;">
        <h4 style="color: var(--text-dim); font-size: 0.9rem; margin-bottom: 0.5rem;">Resources</h4>
        <div style="font-size: 2.5rem; font-weight: 800; color: #51cf66;"><?= $stats['total_resources'] ?></div>
    </div>
    <div class="premium-card" style="text-align: center; padding: 2rem; border-color: <?= $stats['pending_faculty'] > 0 ? 'var(--secondary)' : 'var(--glass-border)' ?>;">
        <h4 style="color: var(--text-dim); font-size: 0.9rem; margin-bottom: 0.5rem;">Pending Faculty</h4>
        <div style="font-size: 2.5rem; font-weight: 800; color: <?= $stats['pending_faculty'] > 0 ? 'var(--secondary)' : 'var(--text-dim)' ?>;">
            <?= $stats['pending_faculty'] ?>
        </div>
    </div>
    <div class="premium-card" style="text-align: center; padding: 2rem;">
        <h4 style="color: var(--text-dim); font-size: 0.9rem; margin-bottom: 0.5rem;">Discussions</h4>
        <div style="font-size: 2.5rem; font-weight: 800; color: var(--primary);"><?= $stats['total_comments'] ?></div>
    </div>
</div>

<div class="glass" style="padding: 2rem;">
    <h3 style="margin-bottom: 1.5rem;">Recent Registrations</h3>
    <table style="width: 100%; border-collapse: collapse; color: var(--text-dim); font-size: 0.9rem;">
        <thead>
            <tr style="border-bottom: 1px solid var(--glass-border); text-align: left;">
                <th style="padding: 1rem 0;">Name</th>
                <th>Role</th>
                <th>Department</th>
                <th>Status</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recent_users as $user): ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 1rem 0; color: white;"><strong><?= htmlspecialchars($user['name']) ?></strong></td>
                    <td><?= $user['role'] ?></td>
                    <td><?= htmlspecialchars($user['department'] ?? 'Unassigned') ?></td>
                    <td>
                        <span style="background: <?= $user['status'] == 'Active' ? '#2b8a3e' : '#e67700' ?>; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; color: white;">
                            <?= $user['status'] ?>
                        </span>
                    </td>
                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top: 2rem;">
        <a href="<?= \Hub\Core\Config::BASE_URL ?>/admin/users" class="btn btn-primary">Manage All Users</a>
    </div>
</div>
