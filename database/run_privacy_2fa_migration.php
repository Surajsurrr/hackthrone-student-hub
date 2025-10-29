<?php
require_once __DIR__ . '/../includes/db_connect.php';

try {
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/migrations/privacy_and_2fa.sql');
    
    // Execute the SQL
    $db->exec($sql);
    
    echo "✓ Privacy and Two-Factor Authentication tables created successfully!\n";
    echo "✓ Migration completed.\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
