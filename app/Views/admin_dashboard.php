<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Welcome, Admin!</h1>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Admin Dashboard</h5>
                        <p class="card-text">Welcome to your admin dashboard, <?php echo htmlspecialchars($user_name); ?>!</p>
                        <p class="card-text">Here you can manage users, courses, announcements, and oversee the entire system.</p>

                        <div class="mt-4">
                            <h6>Quick Actions:</h6>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                <a href="/announcements" class="btn btn-primary me-md-2">View Announcements</a>
                                <button class="btn btn-secondary me-md-2">Manage Users</button>
                                <button class="btn btn-info">System Settings</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
