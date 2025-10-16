<?= $this->extend('template') ?>

<?= $this->section('title') ?>Announcements<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="mb-4">Announcements</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (empty($announcements)): ?>
    <div class="alert alert-info">
        No announcements available at the moment.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($announcements as $announcement): ?>
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($announcement['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($announcement['content']); ?></p>
                        <p class="card-text">
                            <small class="text-muted">
                                Posted on: <?php echo date('F j, Y, g:i a', strtotime($announcement['created_at'])); ?>
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
