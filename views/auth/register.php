<div class="auth-container">
    <div class="premium-card">
        <h1 class="text-gradient">Join the Hub</h1>
        <p style="color: var(--text-dim); margin-bottom: 2rem;">Research and collaborate with peers.</p>

        <form action="<?= \Hub\Core\Config::BASE_URL ?>/register" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" required>
            </div>
            <div class="form-group">
                <label for="email">Institutional Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="user@leads.edu.pk" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="form-group">
                <label for="role">Your Role</label>
                <select name="role" id="role" class="form-control">
                    <option value="Student">Student</option>
                    <option value="Faculty">Faculty</option>
                </select>
            </div>
            <div class="form-group">
            <label for="department">Department</label>
            <select name="department" id="department" class="form-control" required>
                <option value="Computer Science">Computer Science</option>
                <option value="Business Administration">Business Administration</option>
                <option value="Electrical Engineering">Electrical Engineering</option>
                <option value="Law">Law</option>
            </select>
        </div>

        <div class="form-group">
            <label for="semester">Semester</label>
            <select name="semester" id="semester" class="form-control" required>
                <?php for($i=1; $i<=8; $i++): ?>
                    <option value="<?= $i ?>">Semester <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Create Account</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-dim); font-size: 0.9rem;">
            Already have an account? <a href="<?= \Hub\Core\Config::BASE_URL ?>/login" style="color: var(--secondary); text-decoration: none;">Login here</a>
        </p>
    </div>
</div>
