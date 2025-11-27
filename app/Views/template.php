<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $this->renderSection('title') ?> - MyCI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
          <li class="nav-item"><a class="nav-link" href="<?= base_url('/courses') ?>">Courses</a></li>
          <?php $session = session(); $role = strtolower((string) $session->get('role')); ?>
          <?php if ($session->get('isLoggedIn')): ?>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= base_url('/announcements') ?>">Announcements</a></li>
            
            <!-- Notifications Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="notificationBadge" style="display: none; font-size: 0.7em; padding: 0.25em 0.5em;">0</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" id="notificationsDropdownMenu" style="min-width: 300px; max-width: 400px;">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li id="notificationsList">
                  <div class="px-3 py-2 text-muted">
                    <small>Loading notifications...</small>
                  </div>
                </li>
                <li id="noNotifications" style="display: none;">
                  <div class="px-3 py-2 text-muted">
                    <small>No notifications</small>
                  </div>
                </li>
              </ul>
            </li>
            
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <?php if ($session->get('isLoggedIn')): ?>
  <script>
    // Wait for jQuery to be loaded
    if (typeof jQuery === 'undefined') {
      console.error('jQuery is not loaded!');
    }
    
    $(document).ready(function() {
      console.log('Notifications script loaded');
      // Function to fetch and update notifications
      function fetchNotifications() {
        $.get('<?= base_url('/notifications') ?>')
          .done(function(data) {
            console.log('Notifications response:', data);
            if (data.success) {
              // Update badge count
              const badge = $('#notificationBadge');
              if (data.unread_count > 0) {
                badge.text(data.unread_count);
                badge.show();
              } else {
                badge.hide();
              }

              // Update notifications list
              const notificationsList = $('#notificationsList');
              const noNotifications = $('#noNotifications');
              
              if (data.notifications && data.notifications.length > 0) {
                notificationsList.empty().show();
                noNotifications.hide();
                
                data.notifications.forEach(function(notification) {
                  const isRead = notification.is_read == 1 || notification.is_read == '1';
                  const alertClass = isRead ? 'alert-secondary' : 'alert-info';
                  const readBtn = isRead ? '' : 
                    '<button class="btn btn-sm btn-outline-primary mt-2 mark-read-btn" data-id="' + notification.id + '">Mark as Read</button>';
                  
                  const notificationHtml = `
                    <div class="alert ${alertClass} mb-2 mx-2" role="alert" data-notification-id="${notification.id}">
                      <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                          <p class="mb-1 small">${notification.message}</p>
                          <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                          ${readBtn}
                        </div>
                      </div>
                    </div>
                  `;
                  notificationsList.append(notificationHtml);
                });
              } else {
                notificationsList.empty().hide();
                noNotifications.show();
              }
            } else {
              console.error('Notifications fetch failed:', data.message);
              notificationsList.html('<div class="px-3 py-2 text-danger"><small>Error: ' + (data.message || 'Failed to load notifications') + '</small></div>').show();
              noNotifications.hide();
            }
          })
          .fail(function(xhr, status, error) {
            console.error('Failed to fetch notifications:', xhr.responseText, status, error);
            const notificationsList = $('#notificationsList');
            notificationsList.html('<div class="px-3 py-2 text-danger"><small>Error loading notifications. Please refresh the page.</small></div>').show();
            $('#noNotifications').hide();
          });
      }

      // Function to mark notification as read
      function markAsRead(notificationId) {
        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        
        $.ajax({
          url: '<?= base_url('/notifications/mark_read') ?>/' + notificationId,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          success: function(data) {
            if (data.success) {
              // Update the notification styling instead of removing
              const notificationElement = $('[data-notification-id="' + notificationId + '"]');
              notificationElement.removeClass('alert-info').addClass('alert-secondary');
              notificationElement.find('.mark-read-btn').fadeOut(300, function() {
                $(this).remove();
              });
              
              // Update badge count
              const badge = $('#notificationBadge');
              if (data.unread_count > 0) {
                badge.text(data.unread_count);
                badge.show();
              } else {
                badge.hide();
              }
            }
          },
          error: function(xhr, status, error) {
            console.error('Failed to mark notification as read:', error);
            alert('Failed to mark notification as read. Please try again.');
          }
        });
      }

      // Event delegation for mark as read buttons
      $(document).on('click', '.mark-read-btn', function() {
        const notificationId = $(this).data('id');
        markAsRead(notificationId);
      });

      // Initial fetch on page load
      fetchNotifications();

      // Set interval to fetch notifications every 60 seconds
      setInterval(fetchNotifications, 60000);
      
      // Make fetchNotifications available globally so it can be called from other scripts
      window.fetchNotifications = fetchNotifications;
    });
  </script>
  <?php endif; ?>
</body>
</html>
