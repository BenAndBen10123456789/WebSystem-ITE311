<?php

require_once 'vendor/autoload.php';

use Config\Database;

$db = Database::connect();
$forge = $db->forge();

// Create announcements table
$fields = [
    'id' => [
        'type' => 'INT',
        'constraint' => 11,
        'unsigned' => true,
        'auto_increment' => true,
    ],
    'title' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
    ],
    'content' => [
        'type' => 'TEXT',
    ],
    'created_at' => [
        'type' => 'DATETIME',
    ],
    'updated_at' => [
        'type' => 'DATETIME',
    ],
];

$forge->addField($fields);
$forge->addKey('id', true);
$forge->createTable('announcements');

echo "Announcements table created successfully!\n";
