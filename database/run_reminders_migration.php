<?php
// Run this file once to create the reminders table
require_once '../includes/config.php';
require_once '../includes/db_connect.php';

try {
    // Read and execute the migration SQL
    $sql = file_get_contents(__DIR__ . '/migrations/reminders.sql');
    
    $db->exec($sql);
    
    echo "✓ Reminders table created successfully!\n";
    echo "You can now use the reminder functionality.\n";
} catch (PDOException $e) {
    echo "✗ Error creating reminders table: " . $e->getMessage() . "\n";
}
?>
