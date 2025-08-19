<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function up()
{
    $this->forge->addField([
        'id'          => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'username'    => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'password'    => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
        ],
        'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('users');
}

}