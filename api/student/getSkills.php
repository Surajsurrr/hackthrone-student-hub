<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db_connect.php';

header('Content-Type: application/json');

// Check authentication
if (!isLoggedIn() || !hasRole('student')) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    // Check if student_skills table exists
    $db = Database::getInstance()->getConnection();
    
    $result = $db->query("SHOW TABLES LIKE 'student_skills'");
    if ($result->rowCount() == 0) {
        // Table doesn't exist, return empty array
        echo json_encode([
            'success' => true,
            'skills' => []
        ]);
        exit;
    }
    
    // For now, return empty skills array
    // This will be enhanced later with actual functionality
    echo json_encode([
        'success' => true,
        'skills' => []
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error',
        'skills' => []
    ]);
}
?>