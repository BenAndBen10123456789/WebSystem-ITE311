<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Teacher extends BaseController
{
    public function dashboard()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('login_error', 'Please login to access the dashboard.');
            return redirect()->to('login');
        }

        // Check if user has teacher role
        $userRole = strtolower($session->get('role'));
        if ($userRole !== 'teacher') {
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions. Required role: teacher, Current role: ' . $userRole);
            return redirect()->to('/announcements');
        }

        $data = [
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
            'role' => 'teacher'
        ];

        return view('teacher_dashboard', $data);
    }
}
