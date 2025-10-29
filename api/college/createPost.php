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

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['category']) || !isset($input['title']) || !isset($input['content'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Category, title, and content are required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$category = $input['category'];
$title = trim($input['title']);
$content = trim($input['content']);
$image_url = isset($input['image_url']) && !empty($input['image_url']) ? trim($input['image_url']) : null;
$document_url = isset($input['document_url']) && !empty($input['document_url']) ? trim($input['document_url']) : null;
$document_name = isset($input['document_name']) && !empty($input['document_name']) ? trim($input['document_name']) : null;

// Validate category
$allowed_categories = ['research', 'achievement', 'event', 'placement', 'campus-life', 'announcement', 'facilities', 'collaboration'];
if (!in_array($category, $allowed_categories)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid category']);
    exit;
}

// Validate lengths
if (strlen($title) > 200) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Title must be 200 characters or less']);
    exit;
}

if (strlen($content) > 2000) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Content must be 2000 characters or less']);
    exit;
}

try {
    // Get college ID
    $stmt = $pdo->prepare("SELECT id FROM colleges WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $college = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$college) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'College profile not found']);
        exit;
    }
    
    // Insert post
    $stmt = $pdo->prepare("
        INSERT INTO college_posts (college_id, category, title, content, image_url, document_url, document_name, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'published', NOW(), NOW())
    ");
    $stmt->execute([$college['id'], $category, $title, $content, $image_url, $document_url, $document_name]);
    
    $post_id = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'post_id' => $post_id,
        'message' => 'Post created successfully'
    ]);
    
} catch (PDOException $e) {
    error_log("Create post error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
?>
