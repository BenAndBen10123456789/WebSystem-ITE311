<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStudentFieldsToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'program' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'status'
            ],
            'year_level' => [
                'type' => 'INT',
                'constraint' => 1,
                'null' => true,
                'after' => 'program'
            ],
            'section' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'year_level'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['program', 'year_level', 'section']);
    }
}
