<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Academic Hub' ?> | Leads University</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= \Hub\Core\Config::BASE_URL ?>/assets/css/styles.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar shadow-sm">
        <div class="px-4 py-4 mb-2">
            <a href="<?= isset($_SESSION['user_id']) ? '<?= \Hub\Core\Config::BASE_URL ?>/dashboard' : '<?= \Hub\Core\Config::BASE_URL ?>/' ?>" style="text-decoration: none;">
                <div class="brand-logo d-flex align-items-center gap-2">
                    <i class="bi bi-hexagon-fill text-primary"></i> 
                    LEADS HUB
                </div>
            </a>
        </div>
        <div class="nav flex-column flex-grow-1">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/dashboard" class="nav-link <?= !isset($_GET['my_work']) && (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'active' : '' ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/dashboard?my_work=1" class="nav-link <?= isset($_GET['my_work']) ? 'active' : '' ?>"><i class="bi bi-folder-fill"></i> My Workspace</a>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/upload" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'upload') !== false ? 'active' : '' ?>"><i class="bi bi-cloud-arrow-up-fill"></i> Upload</a>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/trash" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'trash') !== false ? 'active' : '' ?>"><i class="bi bi-trash-fill"></i> Recycle Bin</a>
                
                <?php if($_SESSION['user_role'] == 'Faculty'): ?>
                    <a href="<?= \Hub\Core\Config::BASE_URL ?>/collaboration" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'collaboration') !== false ? 'active' : '' ?>"><i class="bi bi-people-fill text-info"></i> Collaboration Hub</a>
                <?php endif; ?>

                <?php if($_SESSION['user_role'] == 'Admin'): ?>
                    <div class="mt-4 px-4 mb-2 text-uppercase text-muted" style="font-size: 0.7rem; font-weight: 700;">System Controls</div>
                    <a href="<?= \Hub\Core\Config::BASE_URL ?>/admin" class="nav-link <?= $_SERVER['REQUEST_URI'] == '<?= \Hub\Core\Config::BASE_URL ?>/admin' ? 'active' : '' ?>"><i class="bi bi-shield-lock-fill text-warning"></i> Admin Center</a>
                    <a href="<?= \Hub\Core\Config::BASE_URL ?>/admin/users" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'admin/user') !== false ? 'active' : '' ?>"><i class="bi bi-people-fill text-primary"></i> Manage Users</a>
                    <a href="<?= \Hub\Core\Config::BASE_URL ?>/admin/departments" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'admin/department') !== false ? 'active' : '' ?>"><i class="bi bi-building text-info"></i> Departments</a>
                    <a href="<?= \Hub\Core\Config::BASE_URL ?>/admin/semesters" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'admin/semester') !== false ? 'active' : '' ?>"><i class="bi bi-calendar3 text-success"></i> Semesters</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/login" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'login') !== false ? 'active' : '' ?>"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/register" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'register') !== false ? 'active' : '' ?>"><i class="bi bi-person-plus-fill"></i> Register</a>
            <?php endif; ?>
        </div>
        
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="p-4 border-top mt-auto border-color">
            <div class="d-flex align-items-center gap-3">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name']) ?>&background=4f46e5&color=fff" alt="User" class="rounded-circle shadow-sm" width="40">
                <div style="overflow: hidden;">
                    <h6 class="mb-0 fw-bold fs-6 text-truncate"><?= htmlspecialchars($_SESSION['user_name']) ?></h6>
                    <small class="text-muted d-block text-truncate" id="sidebar-role"><?= htmlspecialchars($_SESSION['user_role']) ?></small>
                </div>
            </div>
            <a href="<?= \Hub\Core\Config::BASE_URL ?>/logout" class="btn btn-sm btn-outline-danger mt-3 w-100" style="background: transparent; border: 1px solid #ef4444; color: #ef4444;"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
                    <div><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                </div>
            <?php endif; ?>

            <?php require_once __DIR__ . "/../$view.php"; ?>
            
            <footer style="text-align: center; padding: 4rem 0; color: var(--text-muted); font-size: 0.9rem;">
                &copy; 2026 Leads University Academic Resource & Research Hub. Built for Excellence.
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= \Hub\Core\Config::BASE_URL ?>/assets/js/app.js"></script>
</body>
</html>
