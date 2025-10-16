<?php
require_once 'vendor/autoload.php';

use CodeIgniter\Database\Migration;

$config = require 'app/Config/Database.php';
$db = \Config\Database::connect();

$migration = new Migration($db);
$migration->setNamespace('App');

// Run the specific migration
$migration->force('2025-10-16-093400_CreateAnnouncementsTable');

echo "Migration completed successfully!\n";
