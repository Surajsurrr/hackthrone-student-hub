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
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        /* Centered login card using dashboard theme */
        body { background: linear-gradient(135deg, #0b1220 0%, #0f1724 100%); }
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }
        .login-card {
            width: 420px;
            max-width: calc(100% - 40px);
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(2,6,23,0.6);
            background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
            border: 1px solid rgba(255,255,255,0.04);
        }
        .login-card h2 {
            font-size: 1.4rem;
            margin-bottom: 12px;
            font-weight: 700;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .login-card .form-group { margin-bottom: 16px; }
        .login-card label { display:block; color: var(--muted); margin-bottom:6px; font-weight:600; }
        .login-card input[type="email"], .login-card input[type="password"] {
            width:100%; padding:10px 12px; border-radius:8px; border:1px solid rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.02); color: #e6eef6; font-size:0.95rem;
        }
        .login-card input::placeholder { color: rgba(230,238,246,0.5); }
        .btn-primary {
            background: var(--accent-gradient); color: white; border: none; padding: 10px 16px; border-radius:8px; font-weight:700; width:100%;
            box-shadow: 0 8px 20px rgba(102,126,234,0.15);
        }
        .login-card .links { margin-top:12px; text-align:center; color: var(--muted); }
        .alert { padding:10px 12px; border-radius:8px; margin-bottom:12px; }
        .alert-success { background: rgba(16,185,129,0.12); color: #bbf7d0; border:1px solid rgba(16,185,129,0.18); }
        .alert-error { background: rgba(239,68,68,0.07); color: #fecaca; border:1px solid rgba(239,68,68,0.12); }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <h2>Login to <?php echo APP_NAME; ?></h2>
            <?php if ($message): ?>
                <?php 
                $alertClass = (strpos($message, 'successful') !== false) ? 'alert-success' : 'alert-error';
                ?>
                <div class="alert <?php echo $alertClass; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@company.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <div class="links">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
                <p><a href="forgot_password.php">Forgot password?</a></p>
            </div>
        </div>
    </div>
</body>
</html>
