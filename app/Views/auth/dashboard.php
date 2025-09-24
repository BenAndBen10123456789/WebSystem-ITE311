<?= $this->extend('template') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0">Welcome, <?= esc($name ?? 'User') ?></h1>
	<span class="text-muted small">As of <?= esc($now ?? '') ?></span>
</div>

<?php if (($role ?? '') === 'admin'): ?>
	<div class="row g-3 mb-4">
		<div class="col-md-6 col-lg-3">
			<div class="card shadow-sm">
				<div class="card-body">
					<h6 class="text-muted mb-2">Total Users</h6>
					<div class="display-6 fw-bold"><?= esc($totalUsers ?? 0) ?></div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-3">
			<div class="card shadow-sm">
				<div class="card-body">
					<h6 class="text-muted mb-2">Total Courses</h6>
					<div class="display-6 fw-bold"><?= esc($totalCourses ?? 0) ?></div>
				</div>
			</div>
		</div>
	</div>
<?php elseif (($role ?? '') === 'teacher' || ($role ?? '') === 'instructor'): ?>
	<div class="card shadow-sm">
		<div class="card-header bg-white fw-semibold">My Courses</div>
		<div class="card-body p-0">
			<?php if (! empty($myCourses)): ?>
				<ul class="list-group list-group-flush">
					<?php foreach ($myCourses as $course): ?>
						<li class="list-group-item"><?= esc($course['title'] ?? 'Untitled') ?></li>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<p class="text-muted px-3 py-2 mb-0">No courses found.</p>
			<?php endif; ?>
		</div>
	</div>
<?php else: ?>
	<div class="card shadow-sm">
		<div class="card-header bg-white fw-semibold">My Enrollments</div>
		<div class="card-body p-0">
			<?php if (! empty($myEnrollments)): ?>
				<ul class="list-group list-group-flush">
					<?php foreach ($myEnrollments as $en): ?>
						<li class="list-group-item"><?= esc($en['course_title'] ?? 'Course') ?></li>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<p class="text-muted px-3 py-2 mb-0">You are not enrolled in any courses yet.</p>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<?= $this->endSection() ?>


