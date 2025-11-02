<?= $this->extend('template') ?>

<?= $this->section('title') ?>Register<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Register</h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('register_error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('register_error') ?>
                    </div>
                <?php endif; ?>

                <?php if ($validation): ?>
                    <?php if ($validation->hasError('name') || $validation->hasError('email') || $validation->hasError('password') || $validation->hasError('password_confirm') || $validation->hasError('role')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php if ($validation->hasError('name')): ?>
                                    <li><?= $validation->getError('name') ?></li>
                                <?php endif; ?>
                                <?php if ($validation->hasError('email')): ?>
                                    <li><?= $validation->getError('email') ?></li>
                                <?php endif; ?>
                                <?php if ($validation->hasError('password')): ?>
                                    <li><?= $validation->getError('password') ?></li>
                                <?php endif; ?>
                                <?php if ($validation->hasError('password_confirm')): ?>
                                    <li><?= $validation->getError('password_confirm') ?></li>
                                <?php endif; ?>
                                <?php if ($validation->hasError('role')): ?>
                                    <li><?= $validation->getError('role') ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form action="<?= base_url('/register') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control <?= ($validation && $validation->hasError('name')) ? 'is-invalid' : '' ?>" 
                               id="name" name="name" value="<?= old('name') ?>" required>
                        <?php if ($validation && $validation->hasError('name')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('name') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= ($validation && $validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= old('email') ?>" required>
                        <?php if ($validation && $validation->hasError('email')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('email') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control <?= ($validation && $validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                               id="password" name="password" required>
                        <?php if ($validation && $validation->hasError('password')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('password') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control <?= ($validation && $validation->hasError('password_confirm')) ? 'is-invalid' : '' ?>" 
                               id="password_confirm" name="password_confirm" required>
                        <?php if ($validation && $validation->hasError('password_confirm')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('password_confirm') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select <?= ($validation && $validation->hasError('role')) ? 'is-invalid' : '' ?>" 
                                id="role" name="role">
                            <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                            <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                        </select>
                        <?php if ($validation && $validation->hasError('role')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('role') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p>Already have an account? <a href="<?= base_url('/login') ?>">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

