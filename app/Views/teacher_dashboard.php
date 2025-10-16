<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Welcome, Teacher!</h1>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Teacher Dashboard</h5>
                        <p class="card-text">Welcome to your teacher dashboard, <?php echo htmlspecialchars($user_name); ?>!</p>
                        <p class="card-text">Here you can manage your courses, view student submissions, and access teaching resources.</p>

                        <div class="mt-4">
                            <h6>Quick Actions:</h6>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                <a href="/announcements" class="btn btn-primary me-md-2">View Announcements</a>
                                <button class="btn btn-secondary">Manage Courses</button>
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
