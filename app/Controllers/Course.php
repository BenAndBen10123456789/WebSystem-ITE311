<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
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
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully enrolled in the course!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment failed. Please try again.'
            ])->setStatusCode(500); // Internal Server Error
        }
    }
}
