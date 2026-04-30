<div style="max-width: 800px; margin: 0 auto;">
    <div class="premium-card">
        <h1 class="text-gradient">Upload Resource</h1>
        <p style="color: var(--text-dim); margin-bottom: 2rem;">Contribute to the academic community. All uploads are version-tracked.</p>

        <form action="<?= \Hub\Core\Config::BASE_URL ?>/upload" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Analysis of Quantum Algorithms" required>
                </div>
                <div class="form-group">
                    <label for="category_id">Department / Category</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Abstract / Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Briefly describe the research or resource content..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="department">Target Department</label>
                    <select name="department" id="department" class="form-control" required>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Business Administration">Business Administration</option>
                        <option value="Electrical Engineering">Electrical Engineering</option>
                        <option value="Law">Law</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="semester">Target Semester</label>
                    <select name="semester" id="semester" class="form-control" required>
                        <?php for($i=1; $i<=8; $i++): ?>
                            <option value="<?= $i ?>">Semester <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="subject_name">Subject Name (e.g. Software Engineering)</label>
                <input type="text" name="subject_name" id="subject_name" class="form-control" placeholder="Enter the specific subject" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="year">Academic Year</label>
                    <input type="number" name="year" id="year" class="form-control" value="<?= date('Y') ?>" required>
                </div>
                <div class="form-group">
                    <label for="difficulty">Level / Difficulty</label>
                    <select name="difficulty" id="difficulty" class="form-control">
                        <option value="Easy">Beginner / Undergraduate</option>
                        <option value="Intermediate" selected>Intermediate / Graduate</option>
                        <option value="Advanced">Advanced / PhD</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem; border: 2px dashed var(--glass-border); padding: 2rem; border-radius: 12px; text-align: center;">
                <label for="resource_file" style="cursor: pointer;">
                    <span style="color: var(--secondary); font-weight: 600;">Choose a file</span> or drag and drop
                    <br><span style="font-size: 0.8rem; color: var(--text-dim);">PDF, DOCX, PPTX up to 15MB</span>
                </label>
                <input type="file" name="resource_file" id="resource_file" style="display: none;" onchange="document.getElementById('file-name').innerText = this.files[0].name" required>
                <div id="file-name" style="margin-top: 1rem; font-size: 0.9rem; color: var(--secondary);"></div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Publish Resource</button>
                <a href="<?= \Hub\Core\Config::BASE_URL ?>/" class="btn glass" style="flex: 1; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
