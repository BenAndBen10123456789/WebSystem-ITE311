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
        <div class="col-12">
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
                                        <strong><?= esc($course['course_title']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= esc($course['course_description'] ?? '') ?></small>
                                        <br>
                                        <small>Enrolled: <?= isset($course['enrollment_date']) ? esc($course['enrollment_date']) : '-' ?></small>
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
                    
                    // Add course to enrolled courses
                    const enrolledList = document.getElementById('enrolled-courses-list');
                    let enrolledUl = enrolledList.querySelector('ul');
                    
                    // Remove "no enrollments" message if exists
                    const noEnrollmentsMsg = document.getElementById('no-enrollments-msg');
                    if (noEnrollmentsMsg) {
                        noEnrollmentsMsg.remove();
                    }
                    
                    // Create UL if it doesn't exist
                    if (!enrolledUl) {
                        enrolledUl = document.createElement('ul');
                        enrolledUl.className = 'list-group';
                        enrolledList.appendChild(enrolledUl);
                    }
                    
                    // Create new list item for enrolled course
                    const newListItem = document.createElement('li');
                    newListItem.className = 'list-group-item enrolled-course-' + data.course.course_id;
                    newListItem.innerHTML = `
                        <strong>${data.course.course_title}</strong>
                        <br>
                        <small class="text-muted">${data.course.course_description || ''}</small>
                        <br>
                        <small>Enrolled: ${data.course.enrollment_date}</small>
                    `;
                    
                    // Insert at the beginning of the list
                    enrolledUl.insertBefore(newListItem, enrolledUl.firstChild);
                    
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

