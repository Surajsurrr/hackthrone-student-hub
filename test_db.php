<?php
// Database setup script
require_once 'includes/config.php';

echo "<h2>LearnX Database Setup</h2>";

try {
    // Connect to MySQL (without database)
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    echo "<p>✅ Database 'studenthub' created successfully.</p>";
    
    // Connect to the new database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    
    // Read and execute schema
    $schema = file_get_contents('database/schema.sql');
    $pdo->exec($schema);
    echo "<p>✅ Database tables created successfully.</p>";
    
    echo "<p><strong>Setup Complete!</strong> You can now use the website.</p>";
    echo "<p><a href='index.php'>Go to LearnX</a></p>";
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>