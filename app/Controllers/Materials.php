<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MaterialModel;
use App\Models\EnrollmentModel;

class Materials extends BaseController
{
    protected $materialModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    /**
     * Display the file upload form and handle file upload
     * 
     * @param int $course_id Course ID
     */
    public function upload($course_id)
    {
        helper(['form']); // Load form helper for form_open_multipart
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('login_error', 'Please login to access this page.');
            return redirect()->to('login');
        }

        $role = strtolower((string) $session->get('role'));
        
        // Only admin and teacher can upload materials
        if ($role !== 'admin' && $role !== 'teacher') {
            $session->setFlashdata('error', 'You do not have permission to upload materials.');
            return redirect()->to('/dashboard');
        }

        // Check if course exists
        $db = \Config\Database::connect();
        $course = $db->table('courses')
                    ->where('id', $course_id)
                    ->get()
                    ->getRowArray();

        if (!$course) {
            $session->setFlashdata('error', 'Course not found.');
            return redirect()->to('/dashboard');
        }

        // If teacher, verify they own this course (if teacher_id exists)
        if ($role === 'teacher') {
            $userId = (int) $session->get('user_id');
            // Check if teacher_id column exists before querying
            try {
                if ($db->fieldExists('teacher_id', 'courses')) {
                    $courseTeacher = $db->table('courses')
                                       ->where('id', $course_id)
                                       ->where('teacher_id', $userId)
                                       ->countAllResults();
                    
                    if (!$courseTeacher) {
                        $session->setFlashdata('error', 'You can only upload materials to your own courses.');
                        return redirect()->to('/dashboard');
                    }
                }
            } catch (\Exception $e) {
                // Column doesn't exist, allow access for now
            }
            
            // For now, we'll allow if teacher_id doesn't exist in table
            // You can uncomment this after adding teacher_id to courses table
            // if (!$courseTeacher && $db->table('courses')->fieldExists('teacher_id')) {
            //     $session->setFlashdata('error', 'You can only upload materials to your own courses.');
            //     return redirect()->to('/dashboard');
            // }
        }

        // Handle POST request (file upload)
        if ($this->request->getMethod() === 'POST') {
            $validation = \Config\Services::validation();
            
            $rules = [
                'material_file' => [
                    'label' => 'Material File',
                    'rules' => 'uploaded[material_file]|max_size[material_file,10240]|ext_in[material_file,pdf,doc,docx,txt,zip,rar,ppt,pptx,xls,xlsx]',
                    'errors' => [
                        'uploaded' => 'Please select a file to upload.',
                        'max_size' => 'File size must not exceed 10MB.',
                        'ext_in' => 'Allowed file types: PDF, DOC, DOCX, TXT, ZIP, RAR, PPT, PPTX, XLS, XLSX.'
                    ]
                ]
            ];

            if ($this->validate($rules)) {
                $file = $this->request->getFile('material_file');
                
                if ($file->isValid() && !$file->hasMoved()) {
                    // Create upload directory if it doesn't exist
                    $uploadPath = WRITEPATH . 'uploads/materials/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // Generate unique filename
                    $newName = $file->getRandomName();
                    $originalName = $file->getName();
                    
                    // Move file to upload directory
                    if ($file->move($uploadPath, $newName)) {
                        // Save to database
                        $data = [
                            'course_id' => $course_id,
                            'file_name' => $originalName,
                            'file_path' => $uploadPath . $newName
                        ];

                        if ($this->materialModel->insertMaterial($data)) {
                            $session->setFlashdata('success', 'Material uploaded successfully.');
                            return redirect()->to('/admin/course/' . $course_id . '/upload');
                        } else {
                            // Delete file if database insert fails
                            unlink($uploadPath . $newName);
                            $session->setFlashdata('error', 'Failed to save material record. Please try again.');
                        }
                    } else {
                        $session->setFlashdata('error', 'Failed to upload file: ' . $file->getErrorString());
                    }
                } else {
                    $session->setFlashdata('error', 'Invalid file uploaded.');
                }
            } else {
                $errors = $this->validator->getErrors();
                $session->setFlashdata('error', implode('<br>', $errors));
            }
        }

        // Get existing materials for this course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        $data = [
            'course' => $course,
            'course_id' => $course_id,
            'materials' => $materials,
            'validation' => $this->validator
        ];

        return view('materials/upload', $data);
    }

    /**
     * Delete a material record and associated file
     * 
     * @param int $material_id Material ID
     */
    public function delete($material_id)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('login_error', 'Please login to access this page.');
            return redirect()->to('login');
        }

        $role = strtolower((string) $session->get('role'));
        
        // Only admin and teacher can delete materials
        if ($role !== 'admin' && $role !== 'teacher') {
            $session->setFlashdata('error', 'You do not have permission to delete materials.');
            return redirect()->to('/dashboard');
        }

        // Get material record
        $material = $this->materialModel->getMaterialWithCourse($material_id);

        if (!$material) {
            $session->setFlashdata('error', 'Material not found.');
            return redirect()->to('/dashboard');
        }

        // If teacher, verify they own this course
        if ($role === 'teacher') {
            $userId = (int) $session->get('user_id');
            $db = \Config\Database::connect();
            try {
                if ($db->fieldExists('teacher_id', 'courses')) {
                    $courseTeacher = $db->table('courses')
                                       ->where('id', $material['course_id'])
                                       ->where('teacher_id', $userId)
                                       ->countAllResults();
                    
                    if (!$courseTeacher) {
                        $session->setFlashdata('error', 'You can only delete materials from your own courses.');
                        return redirect()->to('/dashboard');
                    }
                }
            } catch (\Exception $e) {
                // Column doesn't exist, allow access for now
            }
        }

        // Delete file if exists
        if (!empty($material['file_path']) && file_exists($material['file_path'])) {
            unlink($material['file_path']);
        }

        // Delete database record
        if ($this->materialModel->delete($material_id)) {
            $session->setFlashdata('success', 'Material deleted successfully.');
        } else {
            $session->setFlashdata('error', 'Failed to delete material record.');
        }

        return redirect()->to('/admin/course/' . $material['course_id'] . '/upload');
    }

    /**
     * Handle file download for enrolled students
     * 
     * @param int $material_id Material ID
     */
    public function download($material_id)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            $session->setFlashdata('login_error', 'Please login to download materials.');
            return redirect()->to('login');
        }

        $userId = (int) $session->get('user_id');
        $role = strtolower((string) $session->get('role'));

        // Get material record with course information
        $material = $this->materialModel->getMaterialWithCourse($material_id);

        if (!$material) {
            $session->setFlashdata('error', 'Material not found.');
            return redirect()->to('/dashboard');
        }

        // Admin and teacher can download any material
        // Students must be enrolled in the course
        if ($role === 'student') {
            // Check if student is enrolled in the course
            if (!$this->enrollmentModel->isAlreadyEnrolled($userId, $material['course_id'])) {
                $session->setFlashdata('error', 'You must be enrolled in this course to download materials.');
                return redirect()->to('/dashboard');
            }
        }

        // Check if file exists
        if (empty($material['file_path']) || !file_exists($material['file_path'])) {
            $session->setFlashdata('error', 'File not found on server.');
            return redirect()->to('/dashboard');
        }

        // Return file download
        // First parameter is the filename shown to user, second is the file path
        return $this->response->download($material['file_name'], $material['file_path']);
    }
}

