<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        // Define the fields
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'enrollment_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ];

        // Define primary key
        $this->forge->addField($fields);
        $this->forge->addKey('id', true); // Primary key

        // Define foreign keys (assuming users and courses tables exist with 'id' primary key)
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');

        // Create the table
        $this->forge->createTable('enrollments');
    }

    public function down()
    {
        // Drop the table to reverse the migration
        $this->forge->dropTable('enrollments');
    }
}
