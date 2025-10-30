<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a student
if (!isLoggedIn() || !hasRole('student')) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Get database connection
$pdo = Database::getInstance()->getConnection();

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['current_password']) || !isset($input['new_password'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$current_password = $input['current_password'];
$new_password = $input['new_password'];
$user_id = $_SESSION['user_id'];

// Validate new password
if (strlen($new_password) < 8) {
    echo json_encode(['success' => false, 'error' => 'New password must be at least 8 characters long']);
    exit;
}

try {
    // Get current password hash from database
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'User not found']);
        exit;
    }
    
    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
        exit;
    }
    
    // Hash new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update password in database
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password_hash, $user_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Password updated successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
        'message' => 'Password changed successfully'
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
