<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 fade-in-up">
    <div>
        <h2 class="fw-bold mb-1 text-gradient" style="font-size: 2.5rem;">Manage Departments</h2>
        <p class="text-muted mb-0">Add or remove academic departments.</p>
    </div>
</div>

<div class="row g-4 mb-5 fade-in-up delay-1">
    <div class="col-md-4">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle-fill text-primary me-2"></i>New Department</h5>
            <form action="<?= \Hub\Core\Config::BASE_URL ?>/admin/departments/create" method="POST">
                <div class="mb-3">
                    <label class="form-label">Department Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Computer Science">
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Department</button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-building text-primary me-2"></i>Existing Departments</h5>
            <div class="table-responsive">
                <table class="table table-custom w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($departments)): ?>
                            <tr><td colspan="3" class="text-center text-muted">No departments found.</td></tr>
                        <?php endif; ?>
                        <?php foreach($departments as $dept): ?>
                        <tr>
                            <td><?= $dept['id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($dept['name']) ?></td>
                            <td class="text-end">
                                <a href="<?= \Hub\Core\Config::BASE_URL ?>/admin/departments/delete/<?= $dept['id'] ?>" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="return confirm('Delete this department?')" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash3-fill"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
