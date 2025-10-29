<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = sanitize($_POST['role']);

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $message = 'Please fill in all fields.';
    } elseif ($password !== $confirmPassword) {
        $message = 'Passwords do not match.';
    } elseif (!in_array($role, ['student', 'college', 'company'])) {
        $message = 'Invalid role selected.';
    } else {
        // Check if email already exists
        $database = Database::getInstance();
        $existingUser = $database->fetchOne("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existingUser) {
            $message = 'Email already registered.';
        } else {
            // Insert user
            $hashedPassword = hashPassword($password);
            $database = Database::getInstance();
            $userId = $database->insert('users', [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => $role
            ]);

            // Create profile based on role
            switch ($role) {
                case 'student':
                    $database->insert('students', ['user_id' => $userId]);
                    break;
                case 'college':
                    $database->insert('colleges', ['user_id' => $userId]);
                    break;
                case 'company':
                    $database->insert('companies', ['user_id' => $userId]);
                    break;
            }

            // Registration successful - redirect to login page
            header('Location: login.php?message=Registration successful! Please login.');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="register-form">
            <h2>Register for <?php echo APP_NAME; ?></h2>
            <?php if ($message): ?>
                <div class="alert <?php echo strpos($message, 'successful') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <label for="role">I am a:</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="student">Student</option>
                        <option value="college">College</option>
                        <option value="company">Company</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
