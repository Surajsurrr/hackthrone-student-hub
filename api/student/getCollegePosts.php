<?php
require_once '../../includes/session.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    // Get all published posts from all colleges
    $stmt = $pdo->prepare("
        SELECT 
            cp.id,
            cp.category,
            cp.title,
            cp.content,
            cp.image_url,
            cp.created_at,
            c.name as college_name,
            c.logo as college_logo,
            c.location as college_location
        FROM college_posts cp
        INNER JOIN colleges c ON cp.college_id = c.id
        WHERE cp.status = 'published'
        ORDER BY cp.created_at DESC
        LIMIT 20
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'posts' => $posts
    ]);
    
} catch (PDOException $e) {
    error_log("Get college posts error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
?>
