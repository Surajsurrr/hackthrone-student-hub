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
if (!isset($input['setting_name']) || !isset($input['value'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$user_id = $_SESSION['user_id'];
$setting_name = $input['setting_name'];
$value = $input['value'] ? 1 : 0;

// Allowed settings
$allowed_settings = ['profile_visibility', 'show_email', 'show_phone'];
if (!in_array($setting_name, $allowed_settings)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid setting name']);
    exit;
}

try {
    // Check if settings record exists
    $stmt = $pdo->prepare("SELECT id FROM student_privacy_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($exists) {
        // Update existing record
        $stmt = $pdo->prepare("UPDATE student_privacy_settings SET $setting_name = ?, updated_at = NOW() WHERE user_id = ?");
        $stmt->execute([$value, $user_id]);
    } else {
        // Insert new record with default values
        $stmt = $pdo->prepare("INSERT INTO student_privacy_settings (user_id, $setting_name, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        $stmt->execute([$user_id, $value]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Privacy setting updated successfully',
        'setting' => $setting_name,
        'value' => (bool)$value
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
