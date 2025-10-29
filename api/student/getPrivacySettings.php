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

$user_id = $_SESSION['user_id'];

try {
    // Get privacy settings
    $stmt = $pdo->prepare("SELECT profile_visibility, show_email, show_phone FROM student_privacy_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If no settings exist, return defaults
    if (!$settings) {
        $settings = [
            'profile_visibility' => 1,
            'show_email' => 0,
            'show_phone' => 0
        ];
    }
    
    echo json_encode([
        'success' => true,
        'profile_visibility' => (bool)$settings['profile_visibility'],
        'show_email' => (bool)$settings['show_email'],
        'show_phone' => (bool)$settings['show_phone']
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
