<?php
require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/session.php';

header('Content-Type: application/json');

// Check if user is logged in and is a company
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Support both old and new field names
$title = isset($data['title']) ? trim($data['title']) : '';
$content = isset($data['content']) ? trim($data['content']) : '';
$postType = isset($data['post_type']) ? trim($data['post_type']) : (isset($data['category']) ? trim($data['category']) : '');
$imageUrl = isset($data['image_url']) ? trim($data['image_url']) : null;
$tags = isset($data['tags']) ? trim($data['tags']) : null;
$status = isset($data['status']) ? trim($data['status']) : 'published';

// Validate required fields
if (empty($title) || empty($content) || empty($postType)) {
    echo json_encode(['success' => false, 'error' => 'Title, content, and post type are required']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Get company profile
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$userId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        echo json_encode(['success' => false, 'error' => 'Company profile not found']);
        exit;
    }

    // Set published_at if status is published
    $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;

    // Insert post into company_posts table
    $stmt = $pdo->prepare("
        INSERT INTO company_posts (
            company_id, title, content, post_type, image_url, tags, status, published_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $company['id'],
        $title,
        $content,
        $postType,
        $imageUrl,
        $tags,
        $status,
        $publishedAt
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Post created successfully',
        'post_id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
