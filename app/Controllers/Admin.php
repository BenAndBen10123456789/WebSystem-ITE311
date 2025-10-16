<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function dashboard()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('login_error', 'Please login to access the dashboard.');
            return redirect()->to('login');
        }

        // Check if user has admin role
        $userRole = strtolower($session->get('role'));
        if ($userRole !== 'admin') {
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions. Required role: admin, Current role: ' . $userRole);
            return redirect()->to('/announcements');
        }

        $data = [
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
            'role' => 'admin'
        ];

        return view('admin_dashboard', $data);
    }
}
