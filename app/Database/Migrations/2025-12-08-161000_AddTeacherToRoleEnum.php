<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeacherToRoleEnum extends Migration
{
    public function up()
    {
        // Expand the role enum to include both 'instructor' and 'teacher'
        $fields = [
            'role' => [
                'name' => 'role',
                'type' => 'ENUM',
                'constraint' => ['student', 'instructor', 'teacher', 'admin'],
                'default' => 'student',
            ],
        ];

        $this->forge->modifyColumn('users', $fields);
    }

    public function down()
    {
        // Revert to original enum (keep 'instructor' but remove 'teacher')
        $fields = [
            'role' => [
                'name' => 'role',
                'type' => 'ENUM',
                'constraint' => ['student', 'instructor', 'admin'],
                'default' => 'student',
            ],
        ];

        $this->forge->modifyColumn('users', $fields);
    }
}
