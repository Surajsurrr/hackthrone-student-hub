<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

// Check for success message from registration
if (isset($_GET['message'])) {
    $message = sanitize($_GET['message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = 'Please fill in all fields.';
    } else {
        try {
            $database = Database::getInstance();
            $user = $database->fetchOne("SELECT * FROM users WHERE email = ?", [$email]);

            if ($user && verifyPassword($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['last_regeneration'] = time();
                
                // Debug information
                if (defined('DEBUG')) {
                    error_log("Login successful for user: " . $user['email'] . " with role: " . $user['role']);
                    error_log("Session data after login: " . print_r($_SESSION, true));
                }

                // Redirect based on role
                switch ($user['role']) {
                    case 'student':
                        header('Location: student/dashboard.php');
                        break;
                    case 'college':
                        header('Location: college/dashboard.php');
                        break;
                    case 'company':
                        header('Location: company/dashboard.php');
                        break;
                    case 'admin':
                        header('Location: admin/dashboard.php');
                        break;
                    default:
                        header('Location: index.php');
                }
                exit;
            } else {
                $message = 'Invalid email or password.';
                if (defined('DEBUG')) {
                    error_log("Login failed for email: " . $email);
                }
            }
        } catch (Exception $e) {
            $message = 'Login error. Please try again.';
            error_log("Login error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2>Login to <?php echo APP_NAME; ?></h2>
            <?php if ($message): ?>
                <?php 
                $alertClass = (strpos($message, 'successful') !== false) ? 'alert-success' : 'alert-error';
                ?>
                <div class="alert <?php echo $alertClass; ?>"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
