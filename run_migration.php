<?php
// Script to run SQL migrations
require_once __DIR__ . '/config/database.php';

try {
    $conn = Database::getConnection();
    $sql = file_get_contents(__DIR__ . '/database/migrations/001_security_tables.sql');
    
    // Execute the SQL
    $conn->exec($sql);
    echo "Migration executed successfully!\n";
} catch (Exception $e) {
    echo "Error executing migration: " . $e->getMessage() . "\n";
    exit(1);
}
