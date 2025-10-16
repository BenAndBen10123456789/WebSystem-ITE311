<?php

// Simple script to run the announcements migration
require_once 'vendor/autoload.php';

use CodeIgniter\Config\Services;
use App\Database\Migrations\CreateAnnouncementsTable;

$migration = new CreateAnnouncementsTable();
$migration->up();

echo "Announcements table created successfully!\n";
