<?php
require_once '../../includes/session.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['enabled'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$user_id = $_SESSION['user_id'];
$enabled = $input['enabled'] ? 1 : 0;

try {
    // Check if 2FA record exists
    $stmt = $pdo->prepare("SELECT id FROM student_2fa_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($exists) {
        // Update existing record
        $stmt = $pdo->prepare("UPDATE student_2fa_settings SET enabled = ?, updated_at = NOW() WHERE user_id = ?");
        $stmt->execute([$enabled, $user_id]);
    } else {
        // Insert new record
        $stmt = $pdo->prepare("INSERT INTO student_2fa_settings (user_id, enabled, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([$user_id, $enabled]);
    }
    
    $status_text = $enabled ? 'enabled' : 'disabled';
    
    echo json_encode([
        'success' => true,
        'message' => "Two-Factor Authentication has been $status_text",
        'enabled' => (bool)$enabled
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
