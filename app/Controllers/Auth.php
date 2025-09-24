<?php

namespace App\Controllers;

class Auth extends BaseController
{

	public function register()
	{
		$session = session();
		if ($session->get('userID')) {
			return redirect()->to(base_url('dashboard'));
		}

		if ($this->request->getMethod() === 'post') {
			$name = trim((string) $this->request->getPost('name'));
			$email = trim((string) $this->request->getPost('email'));
			$password = (string) $this->request->getPost('password');
			$passwordConfirm = (string) $this->request->getPost('password_confirm');

			if ($name === '' || $email === '' || $password === '' || $passwordConfirm === '') {
				return redirect()->back()->withInput()->with('register_error', 'All fields are required.');
			}

			if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return redirect()->back()->withInput()->with('register_error', 'Invalid email address.');
			}

			if ($password !== $passwordConfirm) {
				return redirect()->back()->withInput()->with('register_error', 'Passwords do not match.');
			}

			$userModel = new \App\Models\UserModel();

			if ($userModel->where('email', $email)->first()) {
				return redirect()->back()->withInput()->with('register_error', 'Email is already registered.');
			}

			$passwordHash = password_hash($password, PASSWORD_DEFAULT);

			$userId = $userModel->insert([
				'name' => $name,
				'email' => $email,
				'password' => $passwordHash,
				'role' => 'student',
			], true);

			if (! $userId) {
				return redirect()->back()->withInput()->with('register_error', 'Registration failed.');
			}

			return redirect()->to(base_url('login'))->with('register_success', 'Account created successfully. Please log in.');
		}

		return view('auth/register');
	}

	public function login()
	{
		$session = session();
		if ($session->get('userID')) {
			return redirect()->to(base_url('dashboard'));
		}

		if ($this->request->getMethod() === 'post') {
			$email = trim((string) $this->request->getPost('email'));
			$password = (string) $this->request->getPost('password');

			if ($email === '' || $password === '') {
				return redirect()->back()->withInput()->with('login_error', 'Email and password are required.');
			}

			$userModel = new \App\Models\UserModel();
			$user = $userModel->where('email', $email)->first();
			if ($user) {
				$stored = trim((string) $user['password']);
				$valid = false;
				$info = \password_get_info($stored);
				$isHashed = ($info['algo'] !== 0)
					|| (strpos($stored, '$2y$') === 0)
					|| (strpos($stored, '$argon2') === 0);

				if ($isHashed && password_verify($password, $stored)) {
					$valid = true;
				} elseif (! $isHashed && hash_equals($stored, $password)) {
					$valid = true;
					// upgrade to secure hash
					$userModel->update($user['id'], ['password' => password_hash($password, PASSWORD_DEFAULT)]);
				} elseif (strlen($stored) === 32 && ctype_xdigit($stored) && hash_equals(strtolower($stored), md5($password))) {
					$valid = true;
					// upgrade MD5 to secure hash
					$userModel->update($user['id'], ['password' => password_hash($password, PASSWORD_DEFAULT)]);
				}

				if ($valid) {
					$session->regenerate();
				$session->set([
					'userID' => $user['id'],
					'name' => $user['name'],
					'email' => $user['email'],
					'role' => $user['role'] ?? 'student',
					'isLoggedIn' => true,
				]);
					$session->setFlashdata('welcome', 'Welcome, ' . $user['name'] . '!');
					return redirect()->to('/dashboard');
				}
			}

			return redirect()->back()->with('login_error', 'Invalid credentials');
		}

		return view('auth/login');
	}

	public function logout()
	{
		$session = session();
		$session->destroy();
		return redirect()->to(base_url('login'));
	}

	public function dashboard()
	{
		$session = session();
		if (! $session->get('userID')) {
			return redirect()->to(base_url('login'));
		}

		$role = strtolower((string) $session->get('role'));
		$name = (string) $session->get('name');

		$db = \Config\Database::connect();
		$data = [
			'role' => $role,
			'name' => $name,
			'now' => \CodeIgniter\I18n\Time::now(),
		];

		if ($role === 'admin') {
			$data['totalUsers'] = (int) $db->table('users')->countAllResults();
			$data['totalCourses'] = $db->table('courses')->countAllResults(false);
		}

		if ($role === 'teacher' || $role === 'instructor') {
			$data['myCourses'] = $db->table('courses')->where('teacher_email', $session->get('email'))->limit(5)->get()->getResultArray();
		}

		if ($role === 'student') {
			// Attempt to fetch basic enrollments if tables exist; otherwise provide empty list
			try {
				$data['myEnrollments'] = $db->table('enrollments e')
					->select('e.id, c.title as course_title')
					->join('courses c', 'c.id = e.course_id', 'left')
					->where('e.student_email', $session->get('email'))
					->limit(5)
					->get()
					->getResultArray();
			} catch (\Throwable $th) {
				$data['myEnrollments'] = [];
			}
		}

		return view('auth/dashboard', $data);
	}
}