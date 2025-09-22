<?= $this->extend('template') ?>

<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0">Admin Dashboard</h1>
  <span class="text-muted small">As of <?= esc($now) ?></span>
  </div>

<div class="row g-3 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h6 class="text-muted mb-2">Total Users</h6>
        <div class="display-6 fw-bold"><?= esc($totalUsers) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h6 class="text-muted mb-2">Total Courses</h6>
        <div class="display-6 fw-bold"><?= esc($totalCourses) ?></div>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-header bg-white fw-semibold">Recent Users</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Joined</th>
          </tr>
        </thead>
        <tbody>
          <?php if (! empty($recentUsers)): ?>
            <?php foreach ($recentUsers as $u): ?>
              <tr>
                <td><?= esc($u['id']) ?></td>
                <td><?= esc($u['name']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><span class="badge text-bg-secondary text-capitalize"><?= esc($u['role']) ?></span></td>
                <td><?= esc($u['created_at'] ?? '-') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">No recent users.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>


