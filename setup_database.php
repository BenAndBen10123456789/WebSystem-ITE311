<?php

// Simple database setup script
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_gumabon';

try {
    $mysqli = mysqli_connect($host, $username, $password, $database);

    if (!$mysqli) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }

    // Create announcements table
    $createTableSql = "
        CREATE TABLE IF NOT EXISTS announcements (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL
        )
    ";

    if (mysqli_query($mysqli, $createTableSql)) {
        echo "Announcements table created successfully!\n";
    } else {
        throw new Exception("Error creating table: " . mysqli_error($mysqli));
    }

    // Check if we have any announcements
    $countResult = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM announcements");
    $countRow = mysqli_fetch_assoc($countResult);
    $count = $countRow['count'];

    if ($count == 0) {
        // Insert sample data
        $insertSql = "
            INSERT INTO announcements (title, content, created_at, updated_at) VALUES
            ('Welcome to the Online Student Portal', 'Welcome to our new Online Student Portal! This platform provides students, teachers, and administrators with easy access to academic resources, course materials, and important announcements. We hope you find this system helpful for your academic journey.', NOW(), NOW()),
            ('New Course Registration Period Open', 'The registration period for the upcoming semester is now open. Students can enroll in courses through the portal until the deadline. Please make sure to complete your course selections before the registration period closes to secure your preferred schedule.', NOW(), NOW())
        ";

        if (mysqli_query($mysqli, $insertSql)) {
            echo "Sample announcements inserted successfully!\n";
        } else {
            throw new Exception("Error inserting data: " . mysqli_error($mysqli));
        }
    } else {
        echo "Announcements table already has data!\n";
    }

    echo "Database setup completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
