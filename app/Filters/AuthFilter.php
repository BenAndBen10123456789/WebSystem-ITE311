<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Config\Services;
use App\Models\UserModel;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('isLoggedIn')) {
            if ($request->isAJAX()) {
                return Services::response()->setJSON(['success' => false, 'message' => 'Unauthorized'])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }

        // Verify user status from DB in case it changed after login
        $userId = $session->get('user_id');
        if ($userId) {
            $userModel = new UserModel();
            $user = $userModel->find($userId);
            $status = isset($user['status']) ? $user['status'] : 'active';
            if ($status !== 'active') {
                // Destroy session and force re-login
                $session->destroy();
                if ($request->isAJAX()) {
                    return Services::response()->setJSON(['success' => false, 'message' => 'Account inactive'])->setStatusCode(403);
                }
                return redirect()->to('/login')->with('error', 'Your account is inactive. Please contact the administrator.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
