<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Config\Services;

class RoleAuth implements FilterInterface
{
    /**
     * Run before the request.
     * Checks session role and current URI segment to allow/deny access to admin/teacher routes.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Not logged in
        if (! $session->get('isLoggedIn')) {
            if ($request->isAJAX()) {
                return Services::response()->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }

        $role = strtolower((string) $session->get('role'));

        // Block inactive users immediately
        $userId = $session->get('user_id');
        if ($userId) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            $status = isset($user['status']) ? $user['status'] : 'active';
            if ($status !== 'active') {
                $session->destroy();
                if ($request->isAJAX()) {
                    return Services::response()->setJSON(['success' => false, 'message' => 'Account inactive'])->setStatusCode(403);
                }
                return redirect()->to('/login')->with('error', 'Your account is inactive. Please contact the administrator.');
            }
        }
        $segment = $request->getUri()->getSegment(1);

        // If URI is admin/* then require admin role
        if ($segment === 'admin' && $role !== 'admin') {
            if ($request->isAJAX()) {
                return Services::response()->setJSON(['success' => false, 'message' => 'Access denied'])->setStatusCode(403);
            }
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        // If URI is teacher/* then require teacher/instructor or admin
        if ($segment === 'teacher' && ! in_array($role, ['teacher', 'instructor', 'admin'])) {
            if ($request->isAJAX()) {
                return Services::response()->setJSON(['success' => false, 'message' => 'Access denied'])->setStatusCode(403);
            }
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        // allowed
    }

    /**
     * Run after the request (no-op)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after the request
    }
}
