<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';

echo "<h1>Login Test</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    echo "<h3>Attempting login with:</h3>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    
    try {
        $database = Database::getInstance();
        $user = $database->fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
        
        if ($user) {
            echo "User found in database<br>";
            echo "User ID: " . $user['id'] . "<br>";
            echo "Role: " . $user['role'] . "<br>";
            
            if (verifyPassword($password, $user['password'])) {
                echo "<strong style='color: green;'>Password verification: SUCCESS</strong><br>";
                
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                echo "Session variables set<br>";
                echo "Is logged in: " . (isLoggedIn() ? 'YES' : 'NO') . "<br>";
                echo "Has student role: " . (hasRole('student') ? 'YES' : 'NO') . "<br>";
                
                echo "<br><a href='student/dashboard.php'>Go to Student Dashboard</a>";
            } else {
                echo "<strong style='color: red;'>Password verification: FAILED</strong><br>";
            }
        } else {
            echo "<strong style='color: red;'>User not found in database</strong><br>";
        }
    } catch (Exception $e) {
        echo "<strong style='color: red;'>Database error: " . $e->getMessage() . "</strong><br>";
    }
    
    echo "<hr>";
}

// Show existing users
try {
    $database = Database::getInstance();
    $users = $database->fetchAll("SELECT id, username, email, role FROM users");
    
    echo "<h3>Existing Users in Database:</h3>";
    if ($users) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['role']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No users found in database";
    }
} catch (Exception $e) {
    echo "<strong style='color: red;'>Error loading users: " . $e->getMessage() . "</strong>";
}
?>

<h3>Test Login Form:</h3>
<form method="POST">
    <label>Email: <input type="email" name="email" required></label><br><br>
    <label>Password: <input type="password" name="password" required></label><br><br>
    <button type="submit">Test Login</button>
</form>

<hr>
<a href="debug_session.php">Check Session Debug Info</a> | 
<a href="login.php">Regular Login Page</a>