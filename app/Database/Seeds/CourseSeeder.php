<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'course_code' => 'ITE311',
                'course_title' => 'Web Development',
                'units' => 3,
                'description' => 'Introduction to web development using HTML, CSS, JavaScript, and PHP frameworks.',
            ],
            [
                'course_code' => 'ITE312',
                'course_title' => 'Database Management',
                'units' => 3,
                'description' => 'Learn database design, SQL, and management systems like MySQL.',
            ],
            [
                'course_code' => 'ITE313',
                'course_title' => 'Software Engineering',
                'units' => 3,
                'description' => 'Principles of software development, project management, and best practices.',
            ],
            [
                'course_code' => 'ITE314',
                'course_title' => 'Data Structures and Algorithms',
                'units' => 3,
                'description' => 'Study of fundamental data structures and algorithms for efficient programming.',
            ],
            [
                'course_code' => 'ITE315',
                'course_title' => 'Computer Networks',
                'units' => 3,
                'description' => 'Basics of computer networking, protocols, and network security.',
            ],
        ];

        // Insert data into the courses table
        $this->db->table('courses')->insertBatch($data);
    }
}
