<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use CodeIgniter\API\ResponseTrait; // For JSON responses (optional, but useful)

class Course extends BaseController
{
    use ResponseTrait; // Enables setJSON() for easier JSON responses

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

        // Step 4: Insert new enrollment with current timestamp
        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
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
                'message' => "You have been enrolled in {$courseTitle}",
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
                'message' => 'Successfully enrolled in the course!',
                'course' => [
                    'course_id' => $course['id'] ?? $courseId,
                    'course_title' => $course['course_title'] ?? '',
                    'course_description' => $course['description'] ?? '',
                    'enrollment_date' => $enrollment['enrollment_date'] ?? date('Y-m-d H:i:s')
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

        // If AJAX request, return JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['results' => $results]);
        }

        // Normal request - render the listing view with results
        return view('courses/index', ['courses' => $results, 'search_term' => $term]);
    }
}
