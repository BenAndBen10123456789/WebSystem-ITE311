<?= $this->extend('template') ?>

<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Welcome, Admin!</h1>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Admin Dashboard</h5>
        <p class="card-text">Welcome to your admin dashboard!</p>

        <div class="mt-4">
            <h6>Quick Actions:</h6>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <a href="/announcements" class="btn btn-primary">View Announcements</a>
                <button class="btn btn-secondary" disabled>Manage Users (Coming Soon)</button>
                <button class="btn btn-secondary" disabled>System Settings (Coming Soon)</button>
                <button class="btn btn-secondary" disabled>View Reports (Coming Soon)</button>
            </div>
        </div>

        <div class="mt-4">
            <h6>Administration:</h6>
            <div class="d-flex gap-2 mt-2 flex-wrap">
                <button class="btn btn-warning" disabled>Create Announcement (Coming Soon)</button>
                <button class="btn btn-info" disabled>Manage Courses (Coming Soon)</button>
                <button class="btn btn-success" disabled>User Statistics (Coming Soon)</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
