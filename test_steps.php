<?php
// Minimal test to check each component step by step
echo "<h1>Step-by-step Test</h1>";

echo "<h2>Step 1: Basic PHP</h2>";
echo "<p style='color: green;'>✅ PHP is working</p>";

echo "<h2>Step 2: Config file</h2>";
try {
    require_once 'includes/config.php';
    echo "<p style='color: green;'>✅ Config loaded successfully</p>";
    echo "<p>App Name: " . APP_NAME . "</p>";
    echo "<p>DB Name: " . DB_NAME . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Config error: " . $e->getMessage() . "</p>";
}

echo "<h2>Step 3: Database Connection</h2>";
try {
    require_once 'includes/db_connect.php';
    echo "<p style='color: green;'>✅ Database connection loaded</p>";
    
    $database = Database::getInstance();
    echo "<p style='color: green;'>✅ Database instance created</p>";
    
    $users = $database->fetchAll("SELECT COUNT(*) as count FROM users");
    echo "<p style='color: green;'>✅ Database query successful</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<h2>Step 4: Session</h2>";
try {
    require_once 'includes/session.php';
    echo "<p style='color: green;'>✅ Session started</p>";
    echo "<p>Session ID: " . session_id() . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Session error: " . $e->getMessage() . "</p>";
}

echo "<h2>Step 5: Functions</h2>";
try {
    require_once 'includes/functions.php';
    echo "<p style='color: green;'>✅ Functions loaded</p>";
    
    echo "<p>Is logged in: " . (isLoggedIn() ? 'YES' : 'NO') . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Functions error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Test Navigation</h2>";
echo "<p><a href='test_basic.php'>Basic Test</a></p>";
echo "<p><a href='test_simple.php'>Simple Test</a></p>";
echo "<p><a href='index.php'>Try Index</a></p>";
echo "<p><a href='login.php'>Try Login</a></p>";
echo "<p><a href='student/dashboard.php'>Try Student Dashboard</a></p>";
?>