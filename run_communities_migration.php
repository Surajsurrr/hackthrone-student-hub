<?php
// Run this file once to create the communities tables
require_once 'includes/config.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Creating communities tables...\n";

// Read the SQL file
$sql = file_get_contents('database/migrations/communities.sql');

// Execute the SQL
if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    
    echo "✓ Communities tables created successfully!\n";
    echo "Tables: communities, community_members, community_posts\n";
} else {
    echo "✗ Error: " . $conn->error . "\n";
}

$conn->close();
?>
