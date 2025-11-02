<?= $this->extend('template') ?>

<?= $this->section('title') ?>Upload Materials - <?= esc($course['course_title']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h2>Upload Materials</h2>
        <p class="text-muted">Course: <strong><?= esc($course['course_title']) ?></strong></p>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary mb-3">Back to Dashboard</a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Upload New Material</h5>
            </div>
            <div class="card-body">
                <?= form_open_multipart('/admin/course/' . $course_id . '/upload') ?>
                    <div class="mb-3">
                        <label for="material_file" class="form-label">Select File</label>
                        <input type="file" class="form-control <?= (session()->getFlashdata('validation') && isset($validation['material_file'])) ? 'is-invalid' : '' ?>" 
                               id="material_file" name="material_file" required>
                        <?php if (isset($validation) && $validation->hasError('material_file')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('material_file') ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-text">
                            Allowed file types: PDF, DOC, DOCX, TXT, ZIP, RAR, PPT, PPTX, XLS, XLSX. Maximum size: 10MB.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Material</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Uploaded Materials</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($materials)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Upload Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materials as $material): ?>
                                    <tr>
                                        <td><?= esc($material['file_name']) ?></td>
                                        <td><?= esc($material['created_at'] ?? '-') ?></td>
                                        <td>
                                            <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                                               class="btn btn-sm btn-success" title="Download">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                            <a href="<?= base_url('/materials/delete/' . $material['id']) ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this material?')" 
                                               title="Delete">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No materials uploaded yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

