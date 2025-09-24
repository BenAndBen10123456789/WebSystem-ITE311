<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $this->renderSection('title') ?> - MyCI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="<?= base_url('/') ?>">MyCI</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/about') ?>">About</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/contact') ?>">Contact</a></li>
          <?php $session = session(); $role = strtolower((string) $session->get('role')); ?>
          <?php if ($session->get('isLoggedIn')): ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
            <?php if ($role === 'admin'): ?>
              <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">Admin</a></li>
            <?php elseif ($role === 'teacher' || $role === 'instructor'): ?>
              <li class="nav-item"><a class="nav-link" href="<?= base_url('/teacher/dashboard') ?>">Teacher</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="<?= base_url('/dashboard') ?>">Student</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('/logout') ?>">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('/login') ?>">Login</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <?= $this->renderSection('content') ?>
  </div>
</body>
</html>
