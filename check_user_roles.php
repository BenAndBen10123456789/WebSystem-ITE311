<?php

// Script to check and update user roles
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'lms_gumabon';

try {
    $mysqli = mysqli_connect($host, $username, $password, $database);

    if (!$mysqli) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }

    echo "Current users in database:\n";
    echo "=========================\n";

    $result = mysqli_query($mysqli, "SELECT id, name, email, role FROM users");

    if ($result) {
        while ($user = mysqli_fetch_assoc($result)) {
            echo "ID: {$user['id']} | Name: {$user['name']} | Email: {$user['email']} | Role: {$user['role']}\n";
        }
    } else {
        echo "Error querying users: " . mysqli_error($mysqli) . "\n";
    }

    mysqli_close($mysqli);

    echo "\nIf you see users with incorrect roles, you can update them using the SQL commands below:\n";
    echo "To update a user to teacher role: UPDATE users SET role = 'teacher' WHERE email = 'user@example.com';\n";
    echo "To update a user to admin role: UPDATE users SET role = 'admin' WHERE email = 'user@example.com';\n";
    echo "To update a user to student role: UPDATE users SET role = 'student' WHERE email = 'user@example.com';\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
