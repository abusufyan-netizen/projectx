<div class="auth-container">
    <div class="premium-card">
        <h1 class="text-gradient">Login</h1>
        <p style="color: var(--text-dim); margin-bottom: 2rem;">Access the academic repository.</p>

        <form action="<?= \Hub\Core\Config::BASE_URL ?>/login" method="POST">
            <div class="form-group">
                <label for="email">Institutional Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="user@leads.edu.pk" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Sign In</button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-dim); font-size: 0.9rem;">
            Don't have an account? <a href="<?= \Hub\Core\Config::BASE_URL ?>/register" style="color: var(--secondary); text-decoration: none;">Register here</a>
        </p>
    </div>
</div>
