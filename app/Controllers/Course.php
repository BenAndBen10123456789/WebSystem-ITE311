<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use CodeIgniter\API\ResponseTrait; // For JSON responses (optional, but useful)

class Course extends BaseController
{
    use ResponseTrait; // Enables setJSON() for easier JSON responses

    protected $courseModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
    }

    /**
     * Handle AJAX enrollment request.
     * Expects POST data with 'course_id'.
     * Returns JSON response.
     */
    public function enroll()
    {
        // Step 1: Check if user is logged in
        $userId = session()->get('user_id'); // Adjust based on your session key (e.g., 'id' or 'user_id')
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please log in to enroll in a course.'
            ])->setStatusCode(401); // Unauthorized
        }

        // Step 2: Receive course_id from POST request
        $courseId = $this->request->getPost('course_id');
        if (!$courseId || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID provided.'
            ])->setStatusCode(400); // Bad Request
        }

        // Step 3: Check if already enrolled
        $enrollmentModel = new EnrollmentModel();
        if ($enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ]);
        }

        // Step 4: Insert new enrollment with current timestamp and pending status
        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'status' => 'pending'
            // enrollment_date will be auto-set to current timestamp in the model
        ];

        if ($enrollmentModel->enrollUser($data)) {
            // Get course details for the response
            $db = \Config\Database::connect();
            $course = $db->table('courses')
                ->select('id, course_title, description')
                ->where('id', $courseId)
                ->get()
                ->getRowArray();
            
            // Get enrollment date
            $enrollment = $db->table('enrollments')
                ->select('enrollment_date')
                ->where('user_id', $userId)
                ->where('course_id', $courseId)
                ->get()
                ->getRowArray();
            
            // Step 7: Create notification for the student
            $courseTitle = $course['course_title'] ?? 'the course';
            $notificationModel = new NotificationModel();
            $notificationData = [
                'user_id' => $userId,
                'message' => "Enrollment request submitted for {$courseTitle}. Waiting for teacher approval.",
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Insert notification into the notifications table
            try {
                $notificationModel->insert($notificationData);
            } catch (\Exception $e) {
                // Log error but don't fail enrollment if notification fails
                log_message('error', 'Failed to create notification: ' . $e->getMessage());
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment request submitted successfully! Waiting for teacher approval.',
                'course' => [
                    'course_id' => $course['id'] ?? $courseId,
                    'course_title' => $course['course_title'] ?? '',
                    'course_description' => $course['description'] ?? '',
                    'enrollment_date' => $enrollment['enrollment_date'] ?? date('Y-m-d H:i:s'),
                    'status' => 'pending'
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment failed. Please try again.'
            ])->setStatusCode(500); // Internal Server Error
        }
    }

    /**
     * Show create course form
     */
    public function create()
    {
        return view('courses/create');
    }

    /**
     * Store new course
     */
    public function store()
    {
        $rules = [
            'course_code' => 'required|min_length[2]|max_length[20]',
            'course_title' => 'required|min_length[3]|max_length[255]',
            'description' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'course_code' => $this->request->getPost('course_code'),
            'course_title' => $this->request->getPost('course_title'),
            'description' => $this->request->getPost('description')
        ];

        if ($this->courseModel->insert($data)) {
            return redirect()->to('/courses')->with('success', 'Course created successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create course.');
    }

    /**
     * Show list of courses (normal view)
     */
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('courses');
        $courses = $builder->select('id, course_code, course_title, description')
            ->orderBy('course_title', 'ASC')
            ->get()
            ->getResultArray();

        return view('courses/index', ['courses' => $courses]);
    }

    /**
     * Search courses. Accepts GET or POST param `q`.
     * Returns JSON for AJAX requests, or a view for normal requests.
     */
    public function search()
    {
        $term = $this->request->getGet('q');
        if (empty($term)) {
            $term = $this->request->getPost('q');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('courses');

        if (!empty($term)) {
            $builder->groupStart()
                ->like('course_title', $term)
                ->orLike('course_code', $term)
                ->orLike('description', $term)
            ->groupEnd();
        }

        $results = $builder->select('id, course_code, course_title, description')
            ->orderBy('course_title', 'ASC')
            ->get()
            ->getResultArray();

        // If AJAX request, return JSON with role-based enroll button
        if ($this->request->isAJAX()) {
            $userRole = session()->get('role');
            $showEnrollButton = ($userRole === 'student');

            return $this->response->setJSON([
                'results' => $results,
                'showEnrollButton' => $showEnrollButton
            ]);
        }

        // Normal request - render the listing view with results
        return view('courses/index', ['courses' => $results, 'search_term' => $term]);
    }
}
