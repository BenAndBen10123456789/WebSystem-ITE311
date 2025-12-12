<?= $this->extend('template') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h2>Welcome, <?= esc($user_name) ?>!</h2>
        <p class="text-muted">Role: <?= ucfirst($role) ?></p>
    </div>
</div>

<?php if ($role === 'admin'): ?>
    <!-- Admin Dashboard -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2><?= isset($totalUsers) ? $totalUsers : 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Admins</h5>
                    <h2><?= isset($totalAdmins) ? $totalAdmins : 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Teachers</h5>
                    <h2><?= isset($totalTeachers) ? $totalTeachers : 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Students</h5>
                    <h2><?= isset($totalStudents) ? $totalStudents : 0 ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Courses</h5>
                    <h2><?= isset($totalCourses) ? $totalCourses : 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="<?= base_url('/courses/create') ?>" class="btn btn-success me-2">
                        <i class="bi bi-plus-circle"></i> Create Course
                    </a>
                    <a href="<?= base_url('/admin/manage-users') ?>" class="btn btn-primary">
                        <i class="bi bi-people"></i> Manage Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($recentUsers) && !empty($recentUsers)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Users</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentUsers as $user): ?>
                                    <tr>
                                        <td><?= esc($user['id']) ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= esc($user['role']) ?></td>
                                        <td><?= isset($user['created_at']) ? esc($user['created_at']) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Course Materials Management -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Course Materials Management</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($allCourses) && !empty($allCourses)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allCourses as $course): ?>
                                        <tr>
                                            <td><?= esc($course['course_code'] ?? 'N/A') ?></td>
                                            <td><?= esc($course['course_title']) ?></td>
                                            <td>
                                                <a href="<?= base_url('/admin/course/' . $course['id'] . '/upload') ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-upload"></i> Upload Materials
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No courses available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($role === 'teacher'): ?>
    <!-- Teacher Dashboard -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>My Courses</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($courses) && !empty($courses)): ?>
                        <ul class="list-group">
                            <?php foreach ($courses as $course): ?>
                                <li class="list-group-item"><?= esc($course['course_title']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No courses assigned yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="<?= base_url('/teacher/manage-students') ?>" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-people"></i> Manage Students
                    </a>
                    <a href="<?= base_url('/courses/create') ?>" class="btn btn-success w-100 mb-2">
                        <i class="bi bi-plus-circle"></i> Create Course
                    </a>
                    <a href="#" class="btn btn-secondary w-100">
                        <i class="bi bi-upload"></i> Upload Materials
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Materials Management -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Course Materials Management</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($allCourses) && !empty($allCourses)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allCourses as $course): ?>
                                        <tr>
                                            <td><?= esc($course['course_code'] ?? 'N/A') ?></td>
                                            <td><?= esc($course['course_title']) ?></td>
                                            <td>
                                                <a href="<?= base_url('/admin/course/' . $course['id'] . '/upload') ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-upload"></i> Upload Materials
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No courses available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Pending Enrollment Requests</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Requested Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($pendingEnrollments) && !empty($pendingEnrollments)): ?>
                                    <?php foreach ($pendingEnrollments as $enrollment): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($enrollment['student_name']) ?></strong><br>
                                                <small class="text-muted"><?= esc($enrollment['student_email']) ?></small>
                                            </td>
                                            <td>
                                                <strong><?= esc($enrollment['course_title']) ?></strong>
                                            </td>
                                            <td><?= isset($enrollment['enrollment_date']) ? esc(date('M j, Y', strtotime($enrollment['enrollment_date']))) : '-' ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-success me-2 approve-btn" data-enrollment-id="<?= esc($enrollment['id']) ?>">
                                                    <i class="bi bi-check-circle"></i> Approve
                                                </button>
                                                <button class="btn btn-sm btn-danger reject-btn" data-enrollment-id="<?= esc($enrollment['id']) ?>">
                                                    <i class="bi bi-x-circle"></i> Reject
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No pending enrollment requests.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Submissions</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($notifications) && !empty($notifications)): ?>
                        <ul class="list-group">
                            <?php foreach ($notifications as $notification): ?>
                                <li class="list-group-item">
                                    <strong><?= esc($notification['student_name']) ?></strong> submitted for Course ID: <?= esc($notification['course_id']) ?>
                                    <small class="text-muted"><?= isset($notification['created_at']) ? esc($notification['created_at']) : '' ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No recent submissions.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($role === 'student'): ?>
    <!-- Student Dashboard -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Enrolled Courses</h5>
                </div>
                <div class="card-body">
                    <div id="enrolled-courses-list">
                        <?php if (isset($enrolledCourses) && !empty($enrolledCourses)): ?>
                            <ul class="list-group">
                                <?php foreach ($enrolledCourses as $course): ?>
                                    <li class="list-group-item enrolled-course-<?= esc($course['course_id']) ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong><?= esc($course['course_title']) ?></strong>
                                                <?php if (!empty($course['course_code'])): ?>
                                                    <span class="badge bg-secondary ms-2"><?= esc($course['course_code']) ?></span>
                                                <?php endif; ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?php if (!empty($course['course_description'])): ?>
                                                        <?= esc($course['course_description']) ?>
                                                    <?php else: ?>
                                                        No description available
                                                    <?php endif; ?>
                                                </small>
                                                <br>
                                                <small class="text-info">
                                                    <i class="bi bi-calendar-check"></i>
                                                    Enrolled: <?= isset($course['enrollment_date']) ? esc(date('M j, Y', strtotime($course['enrollment_date']))) : '-' ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-success">
                                                    <i class="bi bi-check-circle-fill"></i> Enrolled
                                                </small>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted" id="no-enrollments-msg">You are not enrolled in any courses yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Pending Enrollment Requests</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($pendingEnrollments) && !empty($pendingEnrollments)): ?>
                        <ul class="list-group">
                            <?php foreach ($pendingEnrollments as $course): ?>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong><?= esc($course['course_title']) ?></strong>
                                            <?php if (!empty($course['course_code'])): ?>
                                                <span class="badge bg-secondary ms-2"><?= esc($course['course_code']) ?></span>
                                            <?php endif; ?>
                                            <br>
                                            <small class="text-muted">
                                                <?php if (!empty($course['course_description'])): ?>
                                                    <?= esc($course['course_description']) ?>
                                                <?php else: ?>
                                                    No description available
                                                <?php endif; ?>
                                            </small>
                                            <br>
                                            <small class="text-warning">
                                                <i class="bi bi-clock"></i>
                                                Requested: <?= isset($course['enrollment_date']) ? esc(date('M j, Y', strtotime($course['enrollment_date']))) : '-' ?>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-warning">
                                                <i class="bi bi-hourglass-split"></i> Pending Approval
                                            </small>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">No pending enrollment requests.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Available Courses</h5>
                </div>
                <div class="card-body">
                    <div id="enrollment-message" class="alert" style="display: none;"></div>
                    <div id="available-courses-list">
                        <?php if (isset($availableCourses) && !empty($availableCourses)): ?>
                            <ul class="list-group">
                                <?php foreach ($availableCourses as $course): ?>
                                    <li class="list-group-item course-item-<?= esc($course['id']) ?>" data-course-id="<?= esc($course['id']) ?>">
                                        <strong><?= esc($course['course_title']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= esc($course['description'] ?? '') ?></small>
                                        <br>
                                        <button class="btn btn-sm btn-primary mt-2 enroll-btn" data-course-id="<?= esc($course['id']) ?>" data-course-title="<?= esc($course['course_title']) ?>">
                                            Enroll
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No available courses.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Materials -->
    <?php if (isset($courseMaterials) && !empty($courseMaterials)): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Course Materials</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($courseMaterials as $courseId => $courseData): ?>
                            <div class="mb-4">
                                <h6 class="text-primary"><?= esc($courseData['course_title']) ?></h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Upload Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($courseData['materials'] as $material): ?>
                                                <tr>
                                                    <td><?= esc($material['file_name']) ?></td>
                                                    <td><?= esc($material['created_at'] ?? '-') ?></td>
                                                    <td>
                                                        <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                                                           class="btn btn-sm btn-success">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php else: ?>
    <!-- Default Dashboard -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <p>Welcome to your dashboard!</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($role === 'teacher'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const approveButtons = document.querySelectorAll('.approve-btn');
    const rejectButtons = document.querySelectorAll('.reject-btn');

    approveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const enrollmentId = this.getAttribute('data-enrollment-id');
            const row = this.closest('tr');

            if (confirm('Are you sure you want to approve this enrollment request?')) {
                fetch('<?= base_url('/teacher/approve-enrollment') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'enrollment_id=' + enrollmentId + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        row.remove(); // Remove the row from the table
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });

    rejectButtons.forEach(button => {
        button.addEventListener('click', function() {
            const enrollmentId = this.getAttribute('data-enrollment-id');
            const row = this.closest('tr');

            if (confirm('Are you sure you want to reject this enrollment request?')) {
                fetch('<?= base_url('/teacher/reject-enrollment') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'enrollment_id=' + enrollmentId + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        row.remove(); // Remove the row from the table
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
    });
});
</script>
<?php endif; ?>

<?php if ($role === 'student'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const enrollButtons = document.querySelectorAll('.enroll-btn');
    
    enrollButtons.forEach(button => {
        button.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            const courseTitle = this.getAttribute('data-course-title');
            const button = this;
            
            // Disable button and show loading state
            button.disabled = true;
            button.textContent = 'Enrolling...';
            
            // Prepare form data
            const formData = new FormData();
            formData.append('course_id', courseId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            
            // Send AJAX request
            fetch('<?= base_url('/course/enroll') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const messageDiv = document.getElementById('enrollment-message');
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = data.message;
                    messageDiv.style.display = 'block';
                    
                    // Hide message after 3 seconds
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 3000);
                    
                    // Remove course from available courses
                    const courseItem = document.querySelector('.course-item-' + courseId);
                    if (courseItem) {
                        courseItem.remove();
                    }
                    
                    // Check if there are no more available courses
                    const availableList = document.getElementById('available-courses-list').querySelector('ul');
                    if (!availableList || availableList.children.length === 0) {
                        document.getElementById('available-courses-list').innerHTML = '<p class="text-muted">No available courses.</p>';
                    }
                    
                    // Add course to pending enrollments
                    const pendingCardBody = document.querySelectorAll('.card .card-body')[1]; // Second card body is pending enrollments
                    let pendingUl = pendingCardBody.querySelector('ul');

                    // Remove "no pending" message if exists
                    const noPendingMsg = pendingCardBody.querySelector('.text-muted');
                    if (noPendingMsg) {
                        noPendingMsg.remove();
                    }

                    // Create UL if it doesn't exist
                    if (!pendingUl) {
                        pendingUl = document.createElement('ul');
                        pendingUl.className = 'list-group';
                        pendingCardBody.appendChild(pendingUl);
                    }

                    // Create new list item for pending course
                    const newListItem = document.createElement('li');
                    newListItem.className = 'list-group-item pending-course-' + data.course.course_id;
                    const courseCodeBadge = data.course.course_code ? `<span class="badge bg-secondary ms-2">${data.course.course_code}</span>` : '';
                    const descriptionText = data.course.course_description || 'No description available';
                    const enrollmentDate = new Date(data.course.enrollment_date).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    newListItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>${data.course.course_title}</strong>
                                ${courseCodeBadge}
                                <br>
                                <small class="text-muted">${descriptionText}</small>
                                <br>
                                <small class="text-warning">
                                    <i class="bi bi-clock"></i>
                                    Requested: ${enrollmentDate}
                                </small>
                            </div>
                            <div class="text-end">
                                <small class="text-warning">
                                    <i class="bi bi-hourglass-split"></i> Pending Approval
                                </small>
                            </div>
                        </div>
                    `;

                    // Insert at the beginning of the list
                    pendingUl.insertBefore(newListItem, pendingUl.firstChild);
                    
                    // Refresh notifications after successful enrollment
                    if (typeof window.fetchNotifications === 'function') {
                        setTimeout(function() {
                            window.fetchNotifications();
                        }, 500); // Small delay to ensure notification is saved in DB
                    }
                } else {
                    // Show error message
                    const messageDiv = document.getElementById('enrollment-message');
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.textContent = data.message;
                    messageDiv.style.display = 'block';
                    
                    // Hide message after 5 seconds
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                    
                    // Re-enable button
                    button.disabled = false;
                    button.textContent = 'Enroll';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const messageDiv = document.getElementById('enrollment-message');
                messageDiv.className = 'alert alert-danger';
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.style.display = 'block';
                
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000);
                
                // Re-enable button
                button.disabled = false;
                button.textContent = 'Enroll';
            });
        });
    });
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
