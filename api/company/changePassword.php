<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a company
if (!isLoggedIn() || !hasRole('company')) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Get database connection
$pdo = Database::getInstance()->getConnection();

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['current_password']) || !isset($data['new_password'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$currentPassword = $data['current_password'];
$newPassword = $data['new_password'];
$userId = $_SESSION['user_id'];

// Validate new password length
if (strlen($newPassword) < 8) {
    echo json_encode(['success' => false, 'error' => 'New password must be at least 8 characters long']);
    exit;
}

try {
    // Get current password hash from database
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'User not found']);
        exit;
    }
    
    // Verify current password
    if (!password_verify($currentPassword, $user['password'])) {
        echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
        exit;
    }
    
    // Hash new password
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$newPasswordHash, $userId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Password updated successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
