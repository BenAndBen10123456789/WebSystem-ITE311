<?= $this->extend('template') ?>

<?= $this->section('title') ?>Manage Users<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Manage Users</h5>
                    <a href="<?= base_url('/admin/manage-users/add') ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Add User
                    </a>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= esc($user['id']) ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <?php if ($user['id'] == 1): ?>
                                                <span class="badge bg-primary">Admin</span>
                                            <?php else: ?>
                                                <select class="form-select form-select-sm change-role" data-user-id="<?= $user['id'] ?>">
                                                    <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                                                    <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                </select>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php $status = isset($user['status']) ? $user['status'] : 'inactive'; ?>
                                            <span class="badge bg-<?= $status === 'active' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('/admin/manage-users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <?php $status = isset($user['status']) ? $user['status'] : 'inactive'; ?>
                                            <?php if ($status === 'active'): ?>
                                                <a href="<?= base_url('/admin/manage-users/deactivate/' . $user['id']) ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                    <i class="bi bi-pause-circle"></i> Deactivate
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('/admin/manage-users/activate/' . $user['id']) ?>" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to activate this user?')">
                                                    <i class="bi bi-play-circle"></i> Activate
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($user['id'] != 1): ?>
                                                <a href="<?= base_url('/admin/manage-users/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelects = document.querySelectorAll('.change-role');
    
    roleSelects.forEach(select => {
        select.addEventListener('change', function() {
            const userId = this.getAttribute('data-user-id');
            const newRole = this.value;
            
            if (confirm('Are you sure you want to change this user\'s role to ' + newRole + '?')) {
                fetch('<?= base_url('/admin/manage-users/change-role/') ?>' + userId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'role=' + encodeURIComponent(newRole) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    location.reload();
                });
            } else {
                // Reset the select to original value
                this.value = this.getAttribute('data-original-role') || 'student';
            }
        });
        
        // Store original role
        select.setAttribute('data-original-role', select.value);
    });
});
</script>
<?= $this->endSection() ?>
