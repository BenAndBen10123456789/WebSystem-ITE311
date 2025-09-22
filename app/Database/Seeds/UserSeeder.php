<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash('password123', PASSWORD_DEFAULT);

        $data = [
            [
                'name' => 'Admin One',
                'email' => 'admin@example.com',
                'password' => $password,
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Instructor One',
                'email' => 'instructor@example.com',
                'password' => $password,
                'role' => 'instructor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Student One',
                'email' => 'student@example.com',
                'password' => $password,
                'role' => 'student',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Upsert by email: insert missing, update existing
        $builder = $this->db->table('users');
        foreach ($data as $row) {
            $existing = $builder->where('email', $row['email'])->get()->getRowArray();
            if ($existing) {
                $builder->where('id', $existing['id'])->update([
                    'name' => $row['name'],
                    'password' => $row['password'],
                    'role' => $row['role'],
                    'updated_at' => $row['updated_at'],
                ]);
            } else {
                $builder->insert($row);
            }
        }
    }
}