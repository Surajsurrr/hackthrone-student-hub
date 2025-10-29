<?php
require_once '../../includes/session.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a college
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'college') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Get college profile
    $stmt = $pdo->prepare("SELECT * FROM colleges WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $college = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$college) {
        // Create empty profile if doesn't exist
        $stmt = $pdo->prepare("INSERT INTO colleges (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
        $college_id = $pdo->lastInsertId();
        
        // Fetch the newly created profile
        $stmt = $pdo->prepare("SELECT * FROM colleges WHERE id = ?");
        $stmt->execute([$college_id]);
        $college = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    echo json_encode([
        'success' => true,
        'profile' => $college
    ]);
    
} catch (PDOException $e) {
    error_log("Get college profile error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
?>
