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
    // Get college ID
    $stmt = $pdo->prepare("SELECT id, name, logo FROM colleges WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $college = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$college) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'College profile not found']);
        exit;
    }
    
    // Get all posts for this college
    $stmt = $pdo->prepare("
        SELECT 
            id, 
            category, 
            title, 
            content, 
            image_url,
            document_url,
            document_name,
            status, 
            created_at, 
            updated_at
        FROM college_posts 
        WHERE college_id = ? AND status = 'published'
        ORDER BY created_at DESC
    ");
    $stmt->execute([$college['id']]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Add college info to each post
    foreach ($posts as &$post) {
        $post['college_name'] = $college['name'];
        $post['college_logo'] = $college['logo'];
    }
    
    echo json_encode([
        'success' => true,
        'posts' => $posts
    ]);
    
} catch (PDOException $e) {
    error_log("Get posts error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
?>
