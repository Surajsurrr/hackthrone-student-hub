<?php
require_once __DIR__ . '/../includes/db_connect.php';

try {
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/migrations/college_posts.sql');
    
    // Execute the SQL
    $db->exec($sql);
    
    echo "✓ College posts table created successfully!\n";
    echo "✓ Migration completed.\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
