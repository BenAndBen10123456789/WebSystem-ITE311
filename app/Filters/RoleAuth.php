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

        // Define role-based access rules
        $accessRules = [
            'admin' => ['admin', 'announcements'], // admins can access admin/* and announcements
            'teacher' => ['teacher', 'announcements'], // teachers can access teacher/* and announcements
            'student' => ['announcements'] // students can only access announcements
        ];

        $allowedPaths = $accessRules[$userRole] ?? [];

        // Check if user has access to the current path
        $hasAccess = false;

        foreach ($allowedPaths as $allowedPath) {
            if (strpos($path, $allowedPath) === 0) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            $session->setFlashdata('error', 'Access Denied: Insufficient Permissions');
            return redirect()->to('/announcements');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
