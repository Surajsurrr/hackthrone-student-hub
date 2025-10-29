<?php
require_once 'includes/db_connect.php';

echo "<h2>College Posts Database Check</h2>";

try {
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'college_posts'");
    if ($stmt->rowCount() == 0) {
        echo "<p style='color: red;'>❌ Table 'college_posts' does not exist!</p>";
        exit;
    }
    echo "<p style='color: green;'>✅ Table 'college_posts' exists</p>";
    
    // Check table structure
    echo "<h3>Table Structure:</h3>";
    $stmt = $pdo->query("DESCRIBE college_posts");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count total posts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM college_posts");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "<h3>Total Posts: {$count}</h3>";
    
    // Show all posts
    if ($count > 0) {
        echo "<h3>All Posts:</h3>";
        $stmt = $pdo->query("
            SELECT cp.*, c.name as college_name 
            FROM college_posts cp 
            LEFT JOIN colleges c ON cp.college_id = c.id 
            ORDER BY cp.created_at DESC
        ");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>College</th><th>Category</th><th>Title</th><th>Status</th><th>Created</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['college_name']}</td>";
            echo "<td>{$row['category']}</td>";
            echo "<td>{$row['title']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠️ No posts found in database</p>";
    }
    
    // Check colleges table
    echo "<h3>Colleges in Database:</h3>";
    $stmt = $pdo->query("SELECT id, name, user_id FROM colleges");
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>College ID</th><th>College Name</th><th>User ID</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['user_id']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
