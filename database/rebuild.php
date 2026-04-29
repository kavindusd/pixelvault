<?php
require 'bootstrap/app.php';
$db = \App\Core\Database::connection();

// Drop all existing tables
$db->exec('SET FOREIGN_KEY_CHECKS = 0');
$tables = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'digital_marketplace'")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    $db->exec("DROP TABLE IF EXISTS `$table`");
}
$db->exec('SET FOREIGN_KEY_CHECKS = 1');

echo "Dropped all old tables.\n";

// Execute schema.sql
$schema = file_get_contents('database/schema.sql');
$db->exec($schema);

echo "Applied new schema.\n";

// Execute 001_initial_seed.sql
$seed = file_get_contents('database/seeds/001_initial_seed.sql');
$db->exec($seed);

echo "Applied seeds.\n";
