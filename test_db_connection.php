<?php
// Database connection test
require_once 'includes/config.php';
require_once 'includes/db_connect.php';

echo "<h2>Database Connection Test</h2>";

try {
    $database = Database::getInstance();
    echo "<p>✅ Database connection successful.</p>";

    // Check if tables exist
    $tables = ['users', 'students', 'colleges', 'companies'];
    foreach ($tables as $table) {
        $result = $database->fetchOne("SHOW TABLES LIKE ?", [$table]);
        if ($result) {
            echo "<p>✅ Table '$table' exists.</p>";
        } else {
            echo "<p>❌ Table '$table' does not exist.</p>";
        }
    }

    // Try to select from users table
    $users = $database->fetchAll("SELECT COUNT(*) as count FROM users");
    echo "<p>✅ Users table query successful. Total users: " . ($users[0]['count'] ?? 0) . "</p>";

} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>