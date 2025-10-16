<?php

require_once 'vendor/autoload.php';

use Config\Database;

$db = Database::connect();

// Insert sample announcements
$data = [
    [
        'title' => 'Welcome to the Online Student Portal',
        'content' => 'Welcome to our new Online Student Portal! This platform provides students, teachers, and administrators with easy access to academic resources, course materials, and important announcements. We hope you find this system helpful for your academic journey.',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'title' => 'New Course Registration Period Open',
        'content' => 'The registration period for the upcoming semester is now open. Students can enroll in courses through the portal until the deadline. Please make sure to complete your course selections before the registration period closes to secure your preferred schedule.',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
];

foreach ($data as $announcement) {
    $db->table('announcements')->insert($announcement);
}

echo "Sample announcements inserted successfully!\n";
