<?= $this->extend('template') ?>

<?= $this->section('content') ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Dashboard</h1>
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">Logout</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" role="alert">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="alert alert-info" role="alert">
        Welcome, <?= esc(session('user_name')) ?>!<br>
        <small class="text-muted">Email: <?= esc(session('user_email')) ?> | Role: <?= esc($role) ?></small>
    </div>

    <?php $roleLower = strtolower((string) $role); ?>

    <?php if ($roleLower === 'admin'): ?>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="h2 mb-0"><?= (int)($totalUsers ?? 0) ?></div>
                        <div class="text-muted">Users</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="h2 mb-0"><?= (int)($totalAdmins ?? 0) ?></div>
                        <div class="text-muted">Admins</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="h2 mb-0"><?= (int)($totalTeachers ?? 0) ?></div>
                        <div class="text-muted">Teachers</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="h2 mb-0"><?= (int)($totalStudents ?? 0) ?></div>
                        <div class="text-muted">Students</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Recent Users</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentUsers)): foreach ($recentUsers as $u): ?>
                                <tr>
                                    <td><?= esc($u['name'] ?? '') ?></td>
                                    <td><?= esc($u['email'] ?? '') ?></td>
                                    <td><?= esc($u['role'] ?? '') ?></td>
                                    <td><?= esc($u['created_at'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="4" class="text-center text-muted">No recent users.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php elseif ($roleLower === 'teacher'): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Your Courses</div>
            <div class="card-body">
                <?php if (!empty($courses)): ?>
                    <ul class="mb-0">
                        <?php foreach ($courses as $c): ?>
                            <li><?= esc($c['course_title'] ?? 'Untitled') ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-muted">No courses found.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Recent Submissions</div>
            <div class="card-body">
                <?php if (!empty($notifications)): ?>
                    <ul class="mb-0">
                        <?php foreach ($notifications as $n): ?>
                            <li><?= esc($n['student_name'] ?? 'Student') ?> - <?= esc($n['created_at'] ?? '') ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-muted">No recent submissions.</div>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif ($roleLower === 'student'): ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Enrolled Courses</div>
            <div class="card-body">
                <?php if (!empty($enrolledCourses)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($enrolledCourses as $c): ?>
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= esc($c['course_title'] ?? 'Untitled') ?></h6>
                                    <small class="text-muted">Enrolled on: <?= esc(date('M j, Y', strtotime($c['enrollment_date'] ?? ''))) ?></small>
                                    <?php if (isset($c['course_description'])): ?>
                                        <p class="mb-0 mt-1 text-muted small"><?= esc(substr($c['course_description'], 0, 100)) ?>...</p>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-primary rounded-pill">Active</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-muted">No enrollments yet. Check out available courses below!</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Available Courses</div>
            <div class="card-body">
                <?php if (!empty($availableCourses)): ?>
                    <div class="row g-3">
                        <?php foreach ($availableCourses as $course): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title mb-2"><?= esc($course['course_title'] ?? 'Untitled') ?></h6>
                                        <?php if (isset($course['description'])): ?>
                                            <p class="card-text text-muted small mb-3"><?= esc(substr($course['description'], 0, 100)) ?>...</p>
                                        <?php endif; ?>
                                        <button class="btn btn-primary btn-sm enroll-btn" 
                                                data-course-id="<?= esc($course['id']) ?>">
                                            Enroll Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-muted">No available courses. All courses are enrolled!</div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="mb-0">Your role is not recognized.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Include jQuery if not already in your template (add this before closing body in template.php or here) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php if ($roleLower === 'student'): ?>
    <script>
        $(document).ready(function() {
            // AJAX Enrollment Script
            $('.enroll-btn').on('click', function(e) {
                e.preventDefault(); // Prevent default button behavior

                var $btn = $(this);
                var courseId = $btn.data('course-id');
                var $card = $btn.closest('.card'); // For hiding the card if needed

                // Show loading state
                $btn.prop('disabled', true).text('Enrolling...');

                // Prepare data (include CSRF if enabled)
                var postData = {
                    course_id: courseId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                };

                $.post('<?= base_url('course/enroll') ?>', postData)
                    .done(function(response) {
                        if (response.success) {
                            // Show success toast
                            var toastMessage = response.message || 'Successfully enrolled!';
                            var $toast = $('#enrollToast');
                            $toast.find('.toast-body').text(toastMessage);
                            $toast.toast('show');

                            // Hide/disable the enroll button and mark as enrolled
                            $btn.hide().after('<span class="badge bg-success ms-2">Enrolled!</span>');

                            // Dynamically update Enrolled Courses list
                            var courseTitle = $btn.closest('.card-body').find('.card-title').text().trim();
                            var courseDescription = $btn.closest('.card-body').find('.card-text').text().trim();
                            var enrollmentDate = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                            // Get the enrolled courses card body
                            var $enrolledCardBody = $('.card-header:contains("Enrolled Courses")').next('.card-body');
                            var $enrolledList = $enrolledCardBody.find('.list-group');
                            if ($enrolledList.length === 0) {
                                // Remove no enrollments message
                                $enrolledCardBody.find('.text-muted').remove();
                                // Create list-group
                                $enrolledList = $('<div class="list-group list-group-flush"></div>');
                                $enrolledCardBody.append($enrolledList);
                            }

                            // Create new item
                            var newItem = `
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">${courseTitle}</h6>
                                        <small class="text-muted">Enrolled on: ${enrollmentDate}</small>
                                        ${courseDescription ? `<p class="mb-0 mt-1 text-muted small">${courseDescription}</p>` : ''}
                                    </div>
                                    <span class="badge bg-primary rounded-pill">Active</span>
                                </a>
                            `;

                            // Prepend to list
                            $enrolledList.prepend(newItem);

                            // Remove the enrolled course from available courses
                            $card.remove();
                        } else {
                            // Show error toast
                            var errorMessage = response.message || 'Enrollment failed.';
                            var $errorToast = $('#errorToast');
                            $errorToast.find('.toast-body').text(errorMessage);
                            $errorToast.toast('show');
                            $btn.prop('disabled', false).text('Enroll Now');
                        }
                    })
                    .fail(function() {
                        // Show error toast
                        var $errorToast = $('#errorToast');
                        $errorToast.find('.toast-body').text('Request failed. Please try again.');
                        $errorToast.toast('show');
                        $btn.prop('disabled', false).text('Enroll Now');
                    });
            });
        });
    </script>
    <?php endif; ?>

    <!-- Success Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="enrollToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
            <div class="toast-header">
                <strong class="me-auto">Enrollment Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <!-- Error Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Enrollment Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>
<?= $this->endSection() ?>