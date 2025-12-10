<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ManageUsers extends Controller
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $users = $this->userModel->findAll();

        return view('admin/manage_users', [
            'users' => $users
        ]);
    }

    public function add()
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[2]|max_length[255]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'role' => 'required|in_list[student,teacher,admin]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Default password for newly created users (admin doesn't input password)
            $defaultPassword = 'password123';

            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => $defaultPassword,
                'role' => $this->request->getPost('role'),
                'status' => 'active'
            ];

            if ($this->userModel->insert($data)) {
                // Inform admin that the user was created with default password
                return redirect()->to('/admin/manage-users')->with('success', 'User added successfully. Default password is "' . $defaultPassword . '".');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to add user');
            }
        }

        return view('admin/add_user');
    }

    public function edit($id)
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/manage-users')->with('error', 'User not found');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[2]|max_length[255]',
                'email' => 'required|valid_email'
            ];

            // Check if email is unique (excluding current user)
            if ($this->request->getPost('email') !== $user['email']) {
                $rules['email'] .= '|is_unique[users.email]';
            }

            // Password is optional for editing
            if ($this->request->getPost('password')) {
                $rules['password'] = 'min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/]';
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email')
            ];

            if ($this->request->getPost('password')) {
                $data['password'] = $this->request->getPost('password');
            }

            if ($this->userModel->update($id, $data)) {
                return redirect()->to('/admin/manage-users')->with('success', 'User updated successfully');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update user');
            }
        }

        return view('admin/edit_user', ['user' => $user]);
    }

    public function changeRole($id)
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Prevent changing main admin's role
        if ($id == 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot change main admin role']);
        }

        $newRole = $this->request->getPost('role');
        // Accept both 'teacher' (UI) and legacy 'instructor'
        if (!in_array($newRole, ['student', 'teacher', 'instructor', 'admin'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid role']);
        }

        if ($this->userModel->update($id, ['role' => $newRole])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Role updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update role']);
        }
    }

    public function deactivate($id)
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/manage-users')->with('error', 'User not found');
        }

        // Prevent deactivating main admin
        if ($id == 1) {
            return redirect()->to('/admin/manage-users')->with('error', 'Cannot deactivate main admin');
        }

        if ($this->userModel->update($id, ['status' => 'inactive'])) {
            return redirect()->to('/admin/manage-users')->with('success', 'User deactivated successfully');
        } else {
            return redirect()->to('/admin/manage-users')->with('error', 'Failed to deactivate user');
        }
    }

    public function activate($id)
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/manage-users')->with('error', 'User not found');
        }

        if ($this->userModel->update($id, ['status' => 'active'])) {
            return redirect()->to('/admin/manage-users')->with('success', 'User activated successfully');
        } else {
            return redirect()->to('/admin/manage-users')->with('error', 'Failed to activate user');
        }
    }

    public function delete($id)
    {
        // Check if user is admin
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/manage-users')->with('error', 'User not found');
        }

        // Prevent deleting main admin
        if ($id == 1) {
            return redirect()->to('/admin/manage-users')->with('error', 'Cannot delete main admin');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/manage-users')->with('success', 'User deleted successfully');
        } else {
            return redirect()->to('/admin/manage-users')->with('error', 'Failed to delete user');
        }
    }
}
