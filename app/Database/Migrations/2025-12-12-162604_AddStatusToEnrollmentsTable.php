<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('enrollments', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
                'after'      => 'enrollment_date'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('enrollments', 'status');
    }
}
