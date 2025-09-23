<?= $this->extend('template') ?>

<?= $this->section('title') ?>
<?= esc($title ?? 'Teacher Dashboard') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0">Teacher Dashboard</h1>
  <span class="text-muted small">As of <?= esc($now ?? '') ?></span>
</div>

<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">My Courses</div>
      <div class="card-body"></div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-white fw-semibold">Quick Actions</div>
      <div class="card-body d-grid gap-2">
        <a href="#" class="btn btn-primary">Create New Course</a>
        <a href="#" class="btn btn-outline-primary">Create Lesson</a>
      </div>
    </div>
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">Notifications</div>
      <div class="card-body"></div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>


