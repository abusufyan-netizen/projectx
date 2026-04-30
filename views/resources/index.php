<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 fade-in-up">
    <div>
        <h2 class="fw-bold mb-1 text-gradient" style="font-size: 2.5rem;">Academic Repository</h2>
        <?php if(isset($_SESSION['user_dept'])): ?>
            <p class="text-primary fw-semibold mb-1">
                ✨ Personalized for <?= htmlspecialchars($_SESSION['user_dept']) ?> | Sem <?= htmlspecialchars($_SESSION['user_sem']) ?>
            </p>
        <?php endif; ?>
        <p class="text-muted mb-0">Explore focused resources curated for your academic journey.</p>
    </div>
</div>

<div class="glass-card mb-5 fade-in-up delay-1 p-4">
    <form action="<?= \Hub\Core\Config::BASE_URL ?>/dashboard" method="GET" class="row g-3 align-items-center">
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="col-auto">
            <a href="<?= \Hub\Core\Config::BASE_URL ?>/dashboard<?= isset($_GET['my_work']) ? '' : '?my_work=1' ?>" class="btn <?= isset($_GET['my_work']) ? 'btn-gradient' : 'btn-light' ?>" style="white-space: nowrap;">
                <?= isset($_GET['my_work']) ? '<i class="bi bi-stars"></i> Viewing Workspace' : '<i class="bi bi-folder"></i> My Workspace' ?>
            </a>
        </div>
        <?php endif; ?>
        
        <div class="col">
            <div class="search-wrapper w-100" style="max-width: 100%;">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control" placeholder="Search by title, description..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
        </div>
        
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['name']) ?>" <?= ($_GET['category'] ?? '') == $cat['name'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-auto">
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel-fill"></i> Filter</button>
        </div>
    </form>
</div>

<div class="fade-in-up delay-2">
    <div class="d-flex align-items-center mb-4 gap-2">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-file-earmark-text text-primary"></i> Available Resources
        </h5>
    </div>

    <div class="table-responsive">
        <?php if(empty($resources)): ?>
            <div class="glass-card text-center p-5">
                <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted">No resources found matching your criteria.</h5>
            </div>
        <?php else: ?>
            <table class="table table-custom w-100">
                <thead>
                    <tr>
                        <th>Resource Detail</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($resources as $res): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary-soft p-2 rounded-3">
                                    <i class="bi bi-file-earmark-medical fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <?= htmlspecialchars($res['title']) ?> 
                                        <span class="text-muted fw-normal" style="font-size: 0.8rem;">v<?= htmlspecialchars($res['version']) ?></span>
                                    </h6>
                                    <?php if($res['subject_name']): ?>
                                        <small class="text-primary fw-medium d-block mb-1"><i class="bi bi-book me-1"></i> <?= htmlspecialchars($res['subject_name']) ?></small>
                                    <?php endif; ?>
                                    <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                                        <?= htmlspecialchars(substr($res['description'], 0, 80)) ?><?= strlen($res['description']) > 80 ? '...' : '' ?>
                                    </small>
                                    <?php if($res['forked_from_author']): ?>
                                        <small class="text-info mt-1 d-block"><i class="bi bi-git me-1"></i> Forked from <?= htmlspecialchars($res['forked_from_author']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($res['author']) ?>&background=random" class="rounded-circle" width="30">
                                <div>
                                    <span class="fw-medium d-block"><?= htmlspecialchars($res['author']) ?></span>
                                    <small class="text-muted" style="font-size: 0.7rem;"><?= date('M d, Y', strtotime($res['created_at'])) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge-soft info"><?= htmlspecialchars($res['category']) ?></span></td>
                        <td>
                            <?php if($res['contributors']): ?>
                                <span class="badge-soft warning" data-bs-toggle="tooltip" title="Collaborators: <?= htmlspecialchars($res['contributors']) ?>">
                                    <i class="bi bi-people-fill me-1"></i> Team
                                </span>
                            <?php else: ?>
                                <span class="badge-soft success"><i class="bi bi-person-fill me-1"></i> Solo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="<?= \Hub\Core\Config::BASE_URL ?>/view/<?= $res['id'] ?>" class="btn btn-sm btn-light text-primary rounded-circle shadow-sm" data-bs-toggle="tooltip" title="View Discussion"><i class="bi bi-chat-dots-fill"></i></a>
                                <a href="/ProjectX/storage/uploads/<?= htmlspecialchars($res['file_path']) ?>" class="btn btn-sm btn-light text-success rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Download" download><i class="bi bi-download"></i></a>
                                
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <?php if($res['user_id'] == $_SESSION['user_id']): ?>
                                        <a href="<?= \Hub\Core\Config::BASE_URL ?>/edit/<?= $res['id'] ?>" class="btn btn-sm btn-light text-warning rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                        
                                        <?php if($res['parent_id']): ?>
                                            <form action="<?= \Hub\Core\Config::BASE_URL ?>/submit-pr/<?= $res['id'] ?>" method="POST" class="d-inline m-0 p-0">
                                                <button type="submit" class="btn btn-sm btn-light text-info rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Submit PR"><i class="bi bi-send-fill"></i></button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <a href="<?= \Hub\Core\Config::BASE_URL ?>/delete/<?= $res['id'] ?>" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="return confirm('Move item to trash?')" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash3-fill"></i></a>
                                    <?php else: ?>
                                        <a href="<?= \Hub\Core\Config::BASE_URL ?>/fork/<?= $res['id'] ?>" class="btn btn-sm btn-light text-secondary rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Fork"><i class="bi bi-bezier2"></i></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
