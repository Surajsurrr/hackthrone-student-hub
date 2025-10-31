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

if (!isset($data['post_id']) && !isset($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'Post ID is required']);
    exit;
}

$postId = $data['post_id'] ?? $data['id'];
$companyId = $_SESSION['user_id'];

try {
    // Check if the post belongs to this company
    $stmt = $pdo->prepare("SELECT id FROM company_posts WHERE id = ? AND company_id = ?");
    $stmt->execute([$postId, $companyId]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'error' => 'Post not found or unauthorized']);
        exit;
    }
    
    // Delete the post
    $stmt = $pdo->prepare("DELETE FROM company_posts WHERE id = ? AND company_id = ?");
    $stmt->execute([$postId, $companyId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Post deleted successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
