<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 fade-in-up">
    <div>
        <h2 class="fw-bold mb-1 text-gradient" style="font-size: 2.5rem;">User Management</h2>
        <p class="text-muted mb-0">Manage roles, departments, semesters, and access levels.</p>
    </div>
</div>

<div class="glass-card p-4 fade-in-up delay-1">
    <div class="table-responsive">
        <table class="table table-custom w-100">
            <thead>
                <tr>
                    <th>Name / Email</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=random" class="rounded-circle" width="30">
                            <div>
                                <span class="fw-bold d-block"><?= htmlspecialchars($user['name']) ?></span>
                                <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-soft <?= $user['role'] == 'Admin' ? 'warning' : ($user['role'] == 'Faculty' ? 'info' : 'success') ?>"><?= $user['role'] ?></span></td>
                    <td><?= htmlspecialchars($user['department_name'] ?? 'Unassigned') ?></td>
                    <td><?= htmlspecialchars($user['semester_name'] ?? 'Unassigned') ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm dropdown-toggle badge-soft <?= $user['status'] == 'Active' ? 'success' : ($user['status'] == 'Pending' ? 'warning' : 'danger') ?>" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none;">
                                <?= $user['status'] ?>
                            </button>
                            <ul class="dropdown-menu shadow-sm border-0">
                                <li><a class="dropdown-item text-success" href="<?= \Hub\Core\Config::BASE_URL ?>/admin/user/status/<?= $user['id'] ?>?status=Active"><i class="bi bi-check-circle-fill me-2"></i>Active</a></li>
                                <li><a class="dropdown-item text-warning" href="<?= \Hub\Core\Config::BASE_URL ?>/admin/user/status/<?= $user['id'] ?>?status=Pending"><i class="bi bi-hourglass-split me-2"></i>Pending</a></li>
                                <li><a class="dropdown-item text-danger" href="<?= \Hub\Core\Config::BASE_URL ?>/admin/user/status/<?= $user['id'] ?>?status=Suspended"><i class="bi bi-slash-circle-fill me-2"></i>Suspended</a></li>
                            </ul>
                        </div>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-light text-primary rounded-circle shadow-sm me-1" data-bs-toggle="modal" data-bs-target="#assignModal<?= $user['id'] ?>" title="Assign Role/Dept"><i class="bi bi-person-gear"></i></button>
                        <a href="<?= \Hub\Core\Config::BASE_URL ?>/admin/user/delete/<?= $user['id'] ?>" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" onclick="return confirm('Remove user permanently?')" title="Delete"><i class="bi bi-trash3-fill"></i></a>
                    </td>
                </tr>

                <!-- Assign Modal -->
                <div class="modal fade" id="assignModal<?= $user['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header border-0 bg-light px-4 py-3">
                                <h5 class="modal-title fw-bold"><i class="bi bi-person-gear text-primary me-2"></i>Assign User</h5>
                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body px-4 py-4 text-start">
                                <form action="<?= \Hub\Core\Config::BASE_URL ?>/admin/user/assign/<?= $user['id'] ?>" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-select">
                                            <option value="Student" <?= $user['role'] == 'Student' ? 'selected' : '' ?>>Student</option>
                                            <option value="Faculty" <?= $user['role'] == 'Faculty' ? 'selected' : '' ?>>Faculty</option>
                                            <option value="Admin" <?= $user['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <select name="department_id" class="form-select">
                                            <option value="">-- Unassigned --</option>
                                            <?php foreach($departments as $dept): ?>
                                                <option value="<?= $dept['id'] ?>" <?= $user['department_id'] == $dept['id'] ? 'selected' : '' ?>><?= htmlspecialchars($dept['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Semester</label>
                                        <select name="semester_id" class="form-select">
                                            <option value="">-- Unassigned --</option>
                                            <?php foreach($semesters as $sem): ?>
                                                <option value="<?= $sem['id'] ?>" <?= $user['semester_id'] == $sem['id'] ? 'selected' : '' ?>><?= htmlspecialchars($sem['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Save Assignments</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
