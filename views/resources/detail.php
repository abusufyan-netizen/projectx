<div style="max-width: 1000px; margin: 0 auto;">
    <a href="<?= \Hub\Core\Config::BASE_URL ?>/" style="color: var(--text-dim); text-decoration: none; margin-bottom: 2rem; display: inline-block; font-size: 0.9rem;">&larr; Back to Repository</a>
    
    <div class="premium-card" style="margin-bottom: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
            <div>
                <span style="font-size: 0.8rem; background: var(--primary); padding: 4px 12px; border-radius: 20px; font-weight: 600; margin-bottom: 1rem; display: inline-block;">
                    <?= $resource['category'] ?>
                </span>
                <h1 class="text-gradient" style="font-size: 2.2rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($resource['title']) ?></h1>
                <?php if($resource['forked_from_author']): ?>
                    <p style="font-size: 0.9rem; color: var(--secondary); margin-bottom: 1rem; font-style: italic;">
                        ✨ This research is forked from original work by <strong><?= htmlspecialchars($resource['forked_from_author']) ?></strong>
                    </p>
                <?php endif; ?>
                <p style="color: var(--text-dim);">By <strong><?= htmlspecialchars($resource['author']) ?></strong> | Published <?= date('M d, Y', strtotime($resource['created_at'])) ?></p>
                <?php if($resource['contributors']): ?>
                    <p style="font-size: 0.85rem; color: var(--secondary); margin-top: 0.5rem;">
                        Collaborators: <?= htmlspecialchars($resource['contributors']) ?>
                    </p>
                <?php endif; ?>
            </div>
            <div style="text-align: right;">
                <span style="color: var(--secondary); font-weight: 700; font-size: 1.2rem;">v<?= $resource['version'] ?></span>
                <div style="margin-top: 1rem; margin-bottom: 1rem;">
                    <a href="/ProjectX/storage/uploads/<?= $resource['file_path'] ?>" class="btn btn-primary" download>Download PDF</a>
                </div>
            <?php if(isset($_SESSION['user_id']) && $resource['user_id'] == $_SESSION['user_id'] && $resource['parent_id']): ?>
                <div style="margin-top: 1rem; padding: 1.5rem; border: 1px solid var(--secondary); border-radius: 12px; background: rgba(212, 175, 55, 0.05);">
                    <h4 style="color: var(--secondary); margin-bottom: 0.5rem; font-size: 1rem;">✨ Substantial Progress?</h4>
                    <p style="font-size: 0.85rem; color: var(--text-dim); margin-bottom: 1rem;">Propose your research updates to the original author for official inclusion.</p>
                    <form action="<?= \Hub\Core\Config::BASE_URL ?>/submit-pr/<?= $resource['id'] ?>" method="POST">
                        <textarea name="message" class="form-control" rows="2" placeholder="Briefly describe your contributions..." style="font-size: 0.85rem; margin-bottom: 0.8rem;"></textarea>
                        <button type="submit" class="btn glass" style="width: 100%; border-color: var(--secondary); color: var(--secondary); font-size: 0.85rem;">Submit Proposal (PR)</button>
                    </form>
                </div>
            <?php endif; ?>
            </div>
        </div>

        <div style="background: rgba(0,0,0,0.2); padding: 2rem; border-radius: 12px; margin-bottom: 2rem; border-left: 4px solid var(--primary);">
            <h3 style="font-size: 1.1rem; color: var(--secondary); margin-bottom: 1rem;">Abstract</h3>
            <p style="white-space: pre-line; line-height: 1.8; color: var(--text-main);"><?= htmlspecialchars($resource['description']) ?></p>
        </div>

        <div style="display: flex; gap: 1rem; align-items: center; font-size: 0.9rem; color: var(--text-dim);">
            <span>Year: <strong><?= $resource['year'] ?></strong></span>
            <span>|</span>
            <span>Difficulty: <strong><?= $resource['difficulty'] ?></strong></span>
        </div>
    </div>

    <!-- Discussion Thread -->
    <section>
        <h2 class="text-gradient">Discussion Thread</h2>
        <p style="color: var(--text-dim); margin-bottom: 2rem;">Feedback from the Leads academic community.</p>

        <?php if(isset($_SESSION['user_id'])): ?>
            <form action="<?= \Hub\Core\Config::BASE_URL ?>/comment/<?= $resource['id'] ?>" method="POST" style="margin-bottom: 3rem;">
                <textarea name="content" class="form-control" rows="3" placeholder="Add to the discussion..." required></textarea>
                <input type="hidden" name="parent_id" value="">
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem; padding: 0.6rem 1.5rem; font-size: 0.9rem;">Post Feedback</button>
            </form>
        <?php else: ?>
            <div class="glass" style="padding: 1rem; text-align: center; margin-bottom: 3rem;">
                <p style="font-size: 0.9rem; color: var(--text-dim);">Please <a href="<?= \Hub\Core\Config::BASE_URL ?>/login" style="color: var(--secondary);">login</a> to join the discussion.</p>
            </div>
        <?php endif; ?>

        <div class="comments-list">
            <?php 
            function renderComments($comments, $level = 0) {
                foreach($comments as $comment) { ?>
                    <div class="comment-item" style="margin-left: <?= $level * 40 ?>px; margin-bottom: 1.5rem;">
                        <div class="glass" style="padding: 1.5rem; border-left: 2px solid <?= $level == 0 ? 'var(--primary)' : 'var(--secondary)' ?>;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                                <strong style="color: var(--secondary);"><?= htmlspecialchars($comment['author']) ?></strong>
                                <span style="color: var(--text-dim); opacity: 0.6;"><?= date('M d, H:i', strtotime($comment['created_at'])) ?></span>
                            </div>
                            <p style="font-size: 0.95rem; line-height: 1.6;"><?= htmlspecialchars($comment['content']) ?></p>
                            
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <button onclick="document.getElementById('reply-form-<?= $comment['id'] ?>').style.display = 'block'" style="background: none; border: none; color: var(--secondary); font-size: 0.75rem; cursor: pointer; margin-top: 0.5rem;">Reply</button>
                                
                                <form id="reply-form-<?= $comment['id'] ?>" action="<?= \Hub\Core\Config::BASE_URL ?>/comment/<?= $comment['resource_id'] ?>" method="POST" style="display: none; margin-top: 1rem;">
                                    <textarea name="content" class="form-control" rows="2" placeholder="Write a reply..." required></textarea>
                                    <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
                                    <button type="submit" class="btn btn-primary" style="margin-top: 0.5rem; padding: 0.4rem 1rem; font-size: 0.8rem;">Post Reply</button>
                                    <button type="button" onclick="this.parentElement.style.display = 'none'" class="btn" style="background: none; color: var(--text-dim); font-size: 0.8rem;">Cancel</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <?php 
                        if(isset($comment['children'])) {
                            renderComments($comment['children'], $level + 1);
                        }
                        ?>
                    </div>
                <?php }
            }
            if(!empty($comments)) {
                renderComments($comments);
            } else {
                echo '<p style="color: var(--text-dim); text-align: center; padding: 2rem;">No comments yet. Be the first to provide feedback.</p>';
            }
            ?>
        </div>
    </section>
</div>
