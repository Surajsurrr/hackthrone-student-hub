<?php
// Simple test without includes
echo "<h1>Database Connection Test</h1>";

try {
    // Test basic database connection
    $pdo = new PDO("mysql:host=localhost;dbname=studenthub", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Users table exists!</p>";
        
        // Count users
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "<p>Users in database: " . $result['count'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Users table does not exist!</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<h2>System Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Current directory: " . __DIR__ . "</p>";
echo "<p>File: " . __FILE__ . "</p>";

// Check if main files exist
echo "<h2>File Existence Check</h2>";
$files_to_check = [
    'index.php',
    'login.php',
    'includes/config.php',
    'includes/session.php',
    'includes/functions.php',
    'includes/db_connect.php',
    'student/dashboard.php'
];

foreach ($files_to_check as $file) {
    $full_path = __DIR__ . '/' . $file;
    if (file_exists($full_path)) {
        echo "<p style='color: green;'>✅ $file exists</p>";
    } else {
        echo "<p style='color: red;'>❌ $file missing</p>";
    }
}

echo "<hr>";
echo "<a href='test_basic.php'>Basic Test</a> | ";
echo "<a href='index.php'>Index</a> | ";
echo "<a href='login.php'>Login</a>";
?>