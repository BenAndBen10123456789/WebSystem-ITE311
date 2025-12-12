<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date', 'status'];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    protected $beforeInsert = ['setEnrollmentDate'];

    protected function setEnrollmentDate(array $data)
    {
        $data['data']['enrollment_date'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function getUserEnrollments($userId, $status = null)
    {
        $query = $this->select('enrollments.*, courses.course_title, courses.description as course_description, enrollments.enrollment_date')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->where('enrollments.user_id', $userId);

        if ($status !== null) {
            $query->where('enrollments.status', $status);
        }

        return $query->orderBy('enrollments.enrollment_date', 'DESC')
                     ->findAll();
    }

    public function isAlreadyEnrolled($userId, $courseId)
    {
        return $this->where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->countAllResults() > 0;
    }

    public function enrollUser($data)
    {
        return $this->insert($data);
    }

    public function getPendingEnrollments()
    {
        return $this->select('enrollments.*, courses.course_title, courses.description as course_description, users.name as student_name, users.email as student_email, enrollments.enrollment_date')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->join('users', 'users.id = enrollments.user_id', 'left')
                    ->where('enrollments.status', 'pending')
                    ->orderBy('enrollments.enrollment_date', 'ASC')
                    ->findAll();
    }

    public function approveEnrollment($enrollmentId)
    {
        return $this->update($enrollmentId, ['status' => 'approved']);
    }

    public function rejectEnrollment($enrollmentId)
    {
        return $this->update($enrollmentId, ['status' => 'rejected']);
    }
}
