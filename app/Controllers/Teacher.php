<?php

namespace App\Controllers;

class Teacher extends BaseController
{
    public function dashboard()
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) $session->get('role'));
        if ($role !== 'teacher' && $role !== 'instructor') {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'title' => 'Teacher Dashboard',
            'now' => date('Y-m-d H:i'),
            'courses' => [
                ['code' => 'ITE311', 'name' => 'Web Systems & Technologies'],
                ['code' => 'ITE312', 'name' => 'Software Engineering'],
            ],
            'notifications' => [
                '2 new assignment submissions to review',
                'Reminder: Create quiz for ITE311 Week 5',
            ],
        ];

        return view('teacher/dashboard', $data);
    }
}


