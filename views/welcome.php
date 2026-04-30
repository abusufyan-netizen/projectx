<div class="d-flex flex-column justify-content-center align-items-center text-center fade-in-up" style="min-height: 80vh;">
    <div class="glass-card p-5" style="max-width: 800px;">
        <div class="brand-logo mb-4" style="font-size: 3rem;">
            <i class="bi bi-hexagon-fill text-primary"></i> 
            LEADS HUB
        </div>
        <h1 class="fw-bold mb-3 text-gradient" style="font-size: 3.5rem;">Academic Resource & Research Hub</h1>
        <p class="text-muted fs-5 mb-5 mx-auto" style="max-width: 600px;">
            Your centralized portal for discovering academic materials, collaborating with peers, and advancing your university research.
        </p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="<?= \Hub\Core\Config::BASE_URL ?>/login" class="btn btn-primary btn-lg px-5 py-3 fs-5 rounded-pill shadow">
                <i class="bi bi-box-arrow-in-right me-2"></i> Access Portal
            </a>
            <a href="<?= \Hub\Core\Config::BASE_URL ?>/register" class="btn btn-light btn-lg px-5 py-3 fs-5 rounded-pill shadow-sm text-primary" style="border: 2px solid var(--border-color);">
                <i class="bi bi-person-plus-fill me-2"></i> Join Leads
            </a>
        </div>
    </div>

    <div class="row w-100 mt-5 pt-4 g-4 text-start">
        <div class="col-md-4 fade-in-up delay-1">
            <div class="glass-card h-100 p-4 border-0 text-center hover-accent">
                <div class="stat-icon bg-primary-soft mx-auto mb-3">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <h4 class="fw-bold">Extensive Resources</h4>
                <p class="text-muted fs-6 mb-0">Access thousands of academic papers, notes, and curated projects from every department.</p>
            </div>
        </div>
        <div class="col-md-4 fade-in-up delay-2">
            <div class="glass-card h-100 p-4 border-0 text-center hover-accent">
                <div class="stat-icon bg-success-soft mx-auto mb-3">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
                <h4 class="fw-bold">Seamless Collaboration</h4>
                <p class="text-muted fs-6 mb-0">Fork projects, submit pull requests, and collaborate directly with faculty and peers.</p>
            </div>
        </div>
        <div class="col-md-4 fade-in-up delay-3">
            <div class="glass-card h-100 p-4 border-0 text-center hover-accent">
                <div class="stat-icon bg-warning-soft mx-auto mb-3">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h4 class="fw-bold">Secure Environment</h4>
                <p class="text-muted fs-6 mb-0">Role-based access ensures your research data is protected and accurately maintained.</p>
            </div>
        </div>
    </div>
</div>
