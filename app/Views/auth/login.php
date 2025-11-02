<?= $this->extend('template') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Login</h3>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('login_error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('login_error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('register_success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('register_success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/login') ?>" method="post">
                    <?= csrf_field() ?>
                    
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

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="<?= base_url('/register') ?>">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

