<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$username = sanitize($input['username'] ?? '');
$email = sanitize($input['email'] ?? '');
$password = $input['password'] ?? '';
$role = sanitize($input['role'] ?? '');

if (empty($username) || empty($email) || empty($password) || empty($role)) {
    jsonResponse(['error' => 'All fields are required'], 400);
}

if (!in_array($role, ['student', 'college', 'company'])) {
    jsonResponse(['error' => 'Invalid role'], 400);
}

global $db;

// Check if email exists
$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(['error' => 'Email already registered'], 409);
}

try {
    $hashedPassword = hashPassword($password);
    $userId = $db->insert('users', [
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
        'role' => $role
    ]);

    // Create profile
    switch ($role) {
        case 'student':
            $db->insert('students', ['user_id' => $userId]);
            break;
        case 'college':
            $db->insert('colleges', ['user_id' => $userId]);
            break;
        case 'company':
            $db->insert('companies', ['user_id' => $userId]);
            break;
    }

    jsonResponse(['success' => true, 'user_id' => $userId], 201);
} catch (Exception $e) {
    jsonResponse(['error' => 'Registration failed'], 500);
}
?>
