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

    // Check if table already exists
    $checkTable = mysqli_query($mysqli, "SHOW TABLES LIKE 'notifications'");
    if (mysqli_num_rows($checkTable) > 0) {
        echo "Notifications table already exists!\n";
        exit;
    }

    // Create notifications table
    $createTableSql = "
        CREATE TABLE IF NOT EXISTS notifications (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) UNSIGNED NOT NULL,
            message VARCHAR(255) NOT NULL,
            is_read TINYINT(1) DEFAULT 0,
            created_at DATETIME NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
        )
    ";

    if (mysqli_query($mysqli, $createTableSql)) {
        echo "Notifications table created successfully!\n";
    } else {
        throw new Exception("Error creating table: " . mysqli_error($mysqli));
    }

    mysqli_close($mysqli);
    echo "Database setup completed!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

