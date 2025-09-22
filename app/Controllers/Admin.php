<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Admin extends BaseController
{
    public function dashboard()
    {
        $session = session();
        if (! $session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $role = strtolower((string) $session->get('role'));
        if ($role !== 'admin') {
            return redirect()->to(base_url('login'))
                ->with('login_error', 'Unauthorized: Admins only.');
        }

        $db = \Config\Database::connect();

        $totalUsers = (int) $db->table('users')->countAllResults();
        $totalCourses = $db->table('courses')->countAllResults(false);

        $recentUsers = $db->table('users')
            ->select('id, name, email, role, created_at')
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('admin/dashboard', [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'recentUsers' => $recentUsers,
            'now' => Time::now(),
        ]);
    }
}


