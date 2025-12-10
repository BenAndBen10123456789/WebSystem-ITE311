<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Courses</h3>
        <div class="d-flex gap-2">
            <a href="<?= base_url('/courses/create') ?>" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Create Course
            </a>
            <form id="search-form" class="d-flex" role="search" onsubmit="return false;">
                <input id="course-search" class="form-control me-2" type="search" placeholder="Search courses..." aria-label="Search" value="<?= isset($search_term) ? esc($search_term) : '' ?>">
                <button id="search-btn" class="btn btn-primary" type="button">Search</button>
            </form>
        </div>
    </div>

    <div id="courses-list" class="row g-3">
        <?php if (isset($courses) && !empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 course-item">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title course-title"><?= esc($course['course_title']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted course-code"><?= esc($course['course_code']) ?></h6>
                            <p class="card-text course-desc"><?= esc($course['description']) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <?php if (session()->get('role') === 'student'): ?>
                                <button class="btn btn-sm btn-outline-primary enroll-btn" data-id="<?= esc($course['id']) ?>">Enroll</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div id="no-results" class="col-12">
                <div class="alert alert-info">No courses available.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(function(){
    const $search = $('#course-search');
    const $list = $('#courses-list');
    const $noResults = $('<div id="no-results" class="col-12"><div class="alert alert-warning">No matching courses found.</div></div>');
    let debounceTimer = null;

    // Client-side instant filtering
    $search.on('input', function(){
        const term = $(this).val().trim().toLowerCase();

        // First do client-side filter
        let matched = 0;
        $('.course-item').each(function(){
            const title = $(this).find('.course-title').text().toLowerCase();
            const code = $(this).find('.course-code').text().toLowerCase();
            const desc = $(this).find('.course-desc').text().toLowerCase();
            if (title.indexOf(term) !== -1 || code.indexOf(term) !== -1 || desc.indexOf(term) !== -1) {
                $(this).show();
                matched++;
            } else {
                $(this).hide();
            }
        });

        if (matched === 0) {
            if ($('#no-results').length === 0) {
                $list.append($noResults);
            }
        } else {
            $('#no-results').remove();
        }

        // Debounced server-side search for longer terms (keeps results up-to-date)
        if (debounceTimer) clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function(){
            const q = $search.val().trim();
            // If empty, don't call server (we already show full set)
            if (q.length === 0) return;

            // Only call when 2+ chars to reduce requests
            if (q.length < 2) return;

            $.get('<?= base_url('/courses/search') ?>', { q: q }, function(resp){
                if (!resp || !resp.results) return;

                // Replace list with server results
                $list.empty();
                if (resp.results.length === 0) {
                    $list.append($noResults);
                } else {
                    resp.results.forEach(function(course){
                        const enrollButtonHtml = resp.showEnrollButton ?
                            `<button class="btn btn-sm btn-outline-primary enroll-btn" data-id="${course.id}">Enroll</button>` : '';

                        const html = `
                        <div class="col-md-4 course-item">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title course-title">${escapeHtml(course.course_title)}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted course-code">${escapeHtml(course.course_code)}</h6>
                                    <p class="card-text course-desc">${escapeHtml(course.description || '')}</p>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    ${enrollButtonHtml}
                                </div>
                            </div>
                        </div>`;
                        $list.append(html);
                    });
                }
            }, 'json');
        }, 350);
    });

    // Search button triggers same behavior
    $('#search-btn').on('click', function(){
        $search.trigger('input');
    });

    // Simple HTML escaper to avoid XSS when injecting HTML from JSON
    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Delegate enroll button click (existing enroll method already uses AJAX)
    $list.on('click', '.enroll-btn', function(){
        const courseId = $(this).data('id');
        $.post('<?= base_url('/course/enroll') ?>', { course_id: courseId }, function(resp){
            if (resp && resp.success) {
                alert(resp.message);
            } else {
                alert(resp && resp.message ? resp.message : 'Enrollment failed');
            }
        }, 'json');
    });
});
</script>

<?= $this->endSection() ?>
