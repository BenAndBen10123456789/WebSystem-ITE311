<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'file_name', 'file_path', 'created_at', 'updated_at'];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    protected $beforeInsert = ['setCreatedAt'];
    protected $beforeUpdate = ['setUpdatedAt'];

    protected function setCreatedAt(array $data)
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function setUpdatedAt(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    /**
     * Insert a new material record
     * 
     * @param array $data Material data
     * @return int|false Insert ID on success, false on failure
     */
    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all materials for a specific course
     * 
     * @param int $course_id Course ID
     * @return array Array of material records
     */
    public function getMaterialsByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get material by ID with course information
     * 
     * @param int $material_id Material ID
     * @return array|null Material record or null
     */
    public function getMaterialWithCourse($material_id)
    {
        return $this->select('materials.*, courses.course_title, courses.id as course_id')
                    ->join('courses', 'courses.id = materials.course_id', 'left')
                    ->where('materials.id', $material_id)
                    ->first();
    }
}

