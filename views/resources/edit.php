<div style="max-width: 800px; margin: 0 auto;">
    <div class="premium-card">
        <h1 class="text-gradient">Edit Research Idea</h1>
        <p style="color: var(--text-dim); margin-bottom: 2rem;">Updating your fork of <strong><?= htmlspecialchars($resource['title']) ?></strong>. This won't affect the original document until you submit a Pull Request.</p>

        <form action="<?= \Hub\Core\Config::BASE_URL ?>/update/<?= $resource['id'] ?>" method="POST">
            <div class="form-group">
                <label for="title">Title (Your Version)</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($resource['title']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Abstract / Your Research Idea</label>
                <textarea name="description" id="description" class="form-control" rows="8" required><?= htmlspecialchars($resource['description']) ?></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Changes</button>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/?my_work=1" class="btn glass" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
