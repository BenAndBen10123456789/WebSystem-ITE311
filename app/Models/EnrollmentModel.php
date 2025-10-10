<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date'];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    protected $beforeInsert = ['setEnrollmentDate'];

    protected function setEnrollmentDate(array $data)
    {
        $data['data']['enrollment_date'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function getUserEnrollments($userId)
    {
        return $this->select('enrollments.*, courses.title as course_title, courses.description as course_description, enrollments.enrollment_date')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->where('enrollments.user_id', $userId)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
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
}
