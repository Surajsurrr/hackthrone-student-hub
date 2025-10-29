<?php
define('DEBUG', true);
require_once 'includes/session.php';
require_once 'includes/functions.php';

echo "<h2>Session Debug Information</h2>";

echo "<h3>Session Status:</h3>";
echo "Session Status: " . session_status() . "<br>";
echo "Session ID: " . session_id() . "<br>";
echo "Session Name: " . session_name() . "<br>";

echo "<h3>Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Login Status:</h3>";
echo "Is Logged In: " . (isLoggedIn() ? 'YES' : 'NO') . "<br>";

if (isLoggedIn()) {
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
    echo "User Role: " . ($_SESSION['role'] ?? 'Not set') . "<br>";
    
    echo "<h3>Current User Data:</h3>";
    $user = getCurrentUser();
    if ($user) {
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    } else {
        echo "Failed to get current user data<br>";
    }
    
    echo "<h3>Role Check:</h3>";
    echo "Has Student Role: " . (hasRole('student') ? 'YES' : 'NO') . "<br>";
}

echo "<h3>Database Connection:</h3>";
try {
    $database = Database::getInstance();
    echo "Database connection: SUCCESS<br>";
    
    // Test query
    $users = $database->fetchAll("SELECT id, username, email, role FROM users LIMIT 5");
    echo "Sample users:<br>";
    echo "<pre>";
    print_r($users);
    echo "</pre>";
} catch (Exception $e) {
    echo "Database connection: FAILED<br>";
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<h3>Server Info:</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current URL: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
?>