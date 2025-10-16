<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('login_error', 'Please login to access this page.');
            return redirect()->to('/login');
        }

        $uri = $request->getUri();
        $path = $uri->getPath();

        // Remove leading slash for consistency
        $path = ltrim($path, '/');

        $userRole = strtolower($session->get('role'));

        // Handle instructor as teacher
        if ($userRole === 'instructor') {
            $userRole = 'teacher';
        }

        log_message('info', 'RoleAuth filter: User ' . $session->get('user_email') . ' with role ' . $userRole . ' accessing path: ' . $path);

        // Define role-based access rules
        $accessRules = [
            'admin' => ['admin'], // admins can access any route starting with /admin
            'teacher' => ['teacher'], // teachers can access routes starting with /teacher
            'student' => ['student', 'announcements'] // students can access /student/* and /announcements
        ];

        $allowedPrefixes = $accessRules[$userRole] ?? [];

        // Check if user has access to the current path
        $hasAccess = false;

        foreach ($allowedPrefixes as $prefix) {
            if (strpos($path, $prefix) === 0) {
                $hasAccess = true;
                break;
            }
        }

        // Special case: allow access to /announcements for all logged-in users
        if (!$hasAccess && $path === 'announcements') {
            $hasAccess = true;
        }

        log_message('info', 'RoleAuth filter: Path: ' . $path);
        log_message('info', 'RoleAuth filter: Allowed prefixes for role ' . $userRole . ': ' . implode(', ', $allowedPrefixes));
        log_message('info', 'RoleAuth filter: Has access: ' . ($hasAccess ? 'true' : 'false'));

        if (!$hasAccess) {
            log_message('error', 'Access denied for user: ' . $session->get('user_email') . ' with role: ' . $userRole . ' trying to access: ' . $path);
            log_message('error', 'Session data: ' . print_r($session->get(), true));
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/announcements');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
