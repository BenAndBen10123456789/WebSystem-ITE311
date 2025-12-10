<?= $this->extend('template') ?>

<?= $this->section('title') ?>Create Course<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Course</h4>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <?= esc(session('error')) ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/courses/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="course_code" class="form-label">Course Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="course_code" name="course_code"
                                   value="<?= old('course_code') ?>" required>
                            <div class="form-text">e.g., CS101, MATH101</div>
                        </div>

                        <div class="mb-3">
                            <label for="course_title" class="form-label">Course Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="course_title" name="course_title"
                                   value="<?= old('course_title') ?>" required>
                            <div class="form-text">e.g., Computer Science Basics, Introduction to Mathematics</div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                      placeholder="Enter course description..."><?= old('description') ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('/courses') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
