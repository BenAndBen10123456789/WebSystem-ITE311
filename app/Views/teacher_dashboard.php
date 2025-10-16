<?= $this->extend('template') ?>

<?= $this->section('title') ?>Teacher Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Welcome, Teacher!</h1>
    <a href="/logout" class="btn btn-danger">Logout</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Teacher Dashboard</h5>
        <p class="card-text">Welcome to your teacher dashboard, <?= htmlspecialchars($user_name) ?>!</p>
        <p class="card-text">You are logged in as: <strong><?= htmlspecialchars($user_email) ?></strong></p>

        <div class="mt-4">
            <h6>Quick Actions:</h6>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <a href="/announcements" class="btn btn-primary">View Announcements</a>
                <button class="btn btn-secondary" disabled>Manage Courses (Coming Soon)</button>
                <button class="btn btn-secondary" disabled>View Students (Coming Soon)</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
