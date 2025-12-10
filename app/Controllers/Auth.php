<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MaterialModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();
        
        if ($this->request->getMethod() === 'POST') {
            // Add detailed logging
            log_message('info', 'Registration POST request received');
            log_message('info', 'POST data: ' . print_r($this->request->getPost(), true));
            
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'matches[password]',
                'role' => 'permit_empty|in_list[student,teacher]'
            ];
            
            if ($this->validate($rules)) {
                log_message('info', 'Validation passed');
                
                try {
                    // Get the data from form
                    $name = trim($this->request->getPost('name'));
                    $email = $this->request->getPost('email');
                    $roleInput = strtolower((string) $this->request->getPost('role'));
                    $role = in_array($roleInput, ['student','teacher'], true) ? $roleInput : 'student';
                    
                    $data = [
                        'name' => $name,
                        'email' => $email,
                        'password' => $this->request->getPost('password'), // Let model handle hashing
                        'role' => $role
                    ];
                    
                    log_message('info', 'Attempting to insert user data: ' . print_r($data, true));
                    
                    // Save user to database
                    $insertResult = $model->insert($data);
                    
                    if ($insertResult) {
                        log_message('info', 'User inserted successfully with ID: ' . $insertResult);
                        $session->setFlashdata('register_success', 'Registration successful. Please login.');
                        return redirect()->to(base_url('login'));
                    } else {
                        // Get the last error for debugging
                        $errors = $model->errors();
                        $errorMessage = 'Registration failed. ';
                        
                        log_message('error', 'Model insert failed. Errors: ' . print_r($errors, true));
                        log_message('error', 'Model validation errors: ' . print_r($model->getValidationMessages(), true));
                        
                        if (!empty($errors)) {
                            $errorMessage .= implode(', ', $errors);
                        } else {
                            $errorMessage .= 'Please try again.';
                        }
                        $session->setFlashdata('register_error', $errorMessage);
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Registration exception: ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                    $session->setFlashdata('register_error', 'Registration failed. Please try again. Error: ' . $e->getMessage());
                }
            } else {
                // Validation failed
                $validationErrors = $this->validator->getErrors();
                log_message('error', 'Validation failed: ' . print_r($validationErrors, true));
                
                $errorMessage = 'Validation failed: ' . implode(', ', $validationErrors);
                $session->setFlashdata('register_error', $errorMessage);
            }
        }
        
        return view('auth/register', [
            'validation' => $this->validator
        ]);
    }

    public function login()
    {
        helper(['form']);
        $session = session();
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];
            
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                
                try {
                    $model = new UserModel();
                    
                    // Find user by email only
                    $user = $model->where('email', $email)->first();
                    
                    if ($user && password_verify($password, $user['password'])) {
                        // Check user status before allowing login
                        $userStatus = isset($user['status']) ? $user['status'] : 'active';
                        if ($userStatus !== 'active') {
                            $session->setFlashdata('login_error', 'Your account is inactive. Please contact the administrator.');
                            return redirect()->back();
                        }
                        // Use the name field directly from database
                        $userName = $user['name'] ?? $user['email'];
                        
                        // Set session data (handle instructor as teacher)
                        $sessionRole = strtolower($user['role'] ?? 'student');
                        if ($sessionRole === 'instructor') {
                            $sessionRole = 'teacher';
                        }

                        $sessionData = [
                            'user_id' => $user['id'],
                            'user_name' => $userName,
                            'user_email' => $user['email'],
                            'role' => $sessionRole,
                            'isLoggedIn' => true
                        ];

                        // Prevent session fixation
                        $session->regenerate();
                        $session->set($sessionData);
                        $session->setFlashdata('success', 'Welcome, ' . $userName . '!');

                        log_message('info', 'Session data set: ' . print_r($sessionData, true));

                        // Role-based redirection with debugging
                        $userRole = strtolower($user['role'] ?? 'student');
                        log_message('info', 'User role for redirection: ' . $userRole . ' for user: ' . $user['email']);

                        // Handle instructor as teacher
                        if ($userRole === 'instructor') {
                            $userRole = 'teacher';
                        }

                        log_message('info', 'Final role for redirection: ' . $userRole);

                        switch ($userRole) {
                            case 'student':
                                log_message('info', 'Redirecting student to dashboard');
                                return redirect()->to('/dashboard');
                            case 'teacher':
                                log_message('info', 'Redirecting teacher to dashboard');
                                return redirect()->to('/dashboard');
                            case 'admin':
                                log_message('info', 'Redirecting admin to dashboard');
                                return redirect()->to('/dashboard');
                            default:
                                log_message('info', 'Unknown role: ' . $userRole . ', redirecting to dashboard');
                                return redirect()->to('/dashboard');
                        }
                    } else {
                        $session->setFlashdata('login_error', 'Invalid email or password.');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Login exception: ' . $e->getMessage());
                    $session->setFlashdata('login_error', 'Login failed. Please try again.');
                }
            } else {
                $session->setFlashdata('login_error', 'Please check your input and try again.');
            }
        }
        
        return view('auth/login', [
            'validation' => $this->validator
        ]);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('login');
    }

    public function dashboard()
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('login_error', 'Please login to access the dashboard.');
            return redirect()->to('login');
        }
        
        $role = strtolower((string) $session->get('role'));
        $userId = (int) $session->get('user_id');

        // Handle instructor as teacher
        if ($role === 'instructor') {
            $role = 'teacher';
        }

        // Prepare role-specific data
        $db = \Config\Database::connect();
        $roleData = [];
        try {
            if ($role === 'admin') {
                $userModel = new UserModel();
                $roleData['totalUsers'] = $userModel->countAllResults();
                $roleData['totalAdmins'] = $userModel->where('role', 'admin')->countAllResults();
                $roleData['totalTeachers'] = $userModel->where('role', 'teacher')->countAllResults();
                $roleData['totalStudents'] = $userModel->where('role', 'student')->countAllResults();
                try {
                    $roleData['totalCourses'] = $db->table('courses')->countAllResults();
                    // Get all courses for admin to upload materials
                    $roleData['allCourses'] = $db->table('courses')
                        ->select('id, course_title, course_code')
                        ->orderBy('course_title', 'ASC')
                        ->get()
                        ->getResultArray();
                } catch (\Throwable $e) {
                    $roleData['totalCourses'] = 0;
                    $roleData['allCourses'] = [];
                }
                $roleData['recentUsers'] = $userModel->orderBy('created_at', 'DESC')->limit(5)->find();
            } elseif ($role === 'teacher') {
                $courses = [];
                $allCourses = [];
                try {
                    // Get courses for display (may not have teacher_id yet)
                    $courses = $db->table('courses')
                        ->select('id, course_title, course_code')
                        ->orderBy('course_title', 'ASC')
                        ->get()
                        ->getResultArray();
                    // For now, show all courses since teacher_id may not exist
                    // Later, you can filter by: ->where('teacher_id', $userId)
                    $roleData['courses'] = $courses;
                    $roleData['allCourses'] = $courses;
                } catch (\Throwable $e) {
                    $courses = [];
                    $roleData['courses'] = [];
                    $roleData['allCourses'] = [];
                }
                $notifications = [];
                try {
                    $notifications = $db->table('submissions')
                        ->select('student_name, course_id, created_at')
                        ->orderBy('created_at', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();
                } catch (\Throwable $e) {
                    $notifications = [];
                }
                $roleData['notifications'] = $notifications;
            } elseif ($role === 'student') {
                $enrolledCourses = [];
                $availableCourses = [];
                try {
                    $enrolledCourses = $db->table('enrollments e')
                        ->select('c.id as course_id, c.course_title as course_title, c.description as course_description, e.enrollment_date')
                        ->join('courses c', 'c.id = e.course_id', 'left')
                        ->where('e.user_id', $userId)
                        ->orderBy('e.enrollment_date', 'DESC')
                        ->get()
                        ->getResultArray();
                } catch (\Throwable $e) {
                    $enrolledCourses = [];
                }
                try {
                    $allCourses = $db->table('courses')
                        ->select('id, course_title, description')
                        ->get()
                        ->getResultArray();
                    $enrolledIds = array_column($enrolledCourses, 'course_id');
                    $availableCourses = array_filter($allCourses, function($course) use ($enrolledIds) {
                        return !in_array($course['id'], $enrolledIds);
                    });
                } catch (\Throwable $e) {
                    $availableCourses = [];
                }
                $roleData['enrolledCourses'] = $enrolledCourses;
                $roleData['availableCourses'] = $availableCourses;
                
                // Get materials for enrolled courses
                $materialModel = new MaterialModel();
                $courseMaterials = [];
                try {
                    foreach ($enrolledCourses as $course) {
                        $materials = $materialModel->getMaterialsByCourse($course['course_id']);
                        if (!empty($materials)) {
                            $courseMaterials[$course['course_id']] = [
                                'course_title' => $course['course_title'],
                                'materials' => $materials
                            ];
                        }
                    }
                } catch (\Throwable $e) {
                    $courseMaterials = [];
                }
                $roleData['courseMaterials'] = $courseMaterials;
            }
        } catch (\Throwable $e) {
            $roleData = [];
        }

        $data = array_merge([
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
            'role' => $role
        ], $roleData);

        return view('auth/dashboard', $data);
    }
}
