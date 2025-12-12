<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Teacher extends BaseController
{
    protected $userModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function manageStudents()
    {
        // Assuming teacher has a course assigned, for simplicity, get all students enrolled in any course
        // In real app, get teacher's courses and students enrolled in those courses

        $data = [
            'title' => 'Manage Students',
            'students' => $this->getStudentsForTeacher()
        ];

        return view('teacher/manage_students', $data);
    }

    private function getStudentsForTeacher()
    {
        // For now, get all students with enrollments
        // TODO: Filter by teacher's courses

        return $this->userModel
            ->select('users.*, enrollments.enrollment_date, enrollments.course_id')
            ->join('enrollments', 'enrollments.user_id = users.id', 'left')
            ->where('users.role', 'student')
            ->findAll();
    }

    public function approveEnrollment()
    {
        $enrollmentId = $this->request->getPost('enrollment_id');
        if (!$enrollmentId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid enrollment ID.']);
        }

        if ($this->enrollmentModel->approveEnrollment($enrollmentId)) {
            // Notify student
            $enrollment = $this->enrollmentModel->find($enrollmentId);
            if ($enrollment) {
                $notificationModel = new \App\Models\NotificationModel();
                $notificationData = [
                    'user_id' => $enrollment['user_id'],
                    'message' => "Your enrollment request for course ID {$enrollment['course_id']} has been approved.",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $notificationModel->insert($notificationData);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Enrollment approved successfully.']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve enrollment.']);
    }

    public function rejectEnrollment()
    {
        $enrollmentId = $this->request->getPost('enrollment_id');
        if (!$enrollmentId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid enrollment ID.']);
        }

        if ($this->enrollmentModel->rejectEnrollment($enrollmentId)) {
            // Notify student
            $enrollment = $this->enrollmentModel->find($enrollmentId);
            if ($enrollment) {
                $notificationModel = new \App\Models\NotificationModel();
                $notificationData = [
                    'user_id' => $enrollment['user_id'],
                    'message' => "Your enrollment request for course ID {$enrollment['course_id']} has been rejected.",
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $notificationModel->insert($notificationData);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Enrollment rejected successfully.']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject enrollment.']);
    }
}
