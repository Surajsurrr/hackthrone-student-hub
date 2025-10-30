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

if (!isset($data['category']) || !isset($data['title']) || !isset($data['content'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$category = trim($data['category']);
$title = trim($data['title']);
$content = trim($data['content']);
$companyId = $_SESSION['user_id'];

// Validate inputs
if (empty($category) || empty($title) || empty($content)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

if (strlen($title) > 200) {
    echo json_encode(['success' => false, 'error' => 'Title is too long (max 200 characters)']);
    exit;
}

if (strlen($content) > 2000) {
    echo json_encode(['success' => false, 'error' => 'Content is too long (max 2000 characters)']);
    exit;
}

try {
    // Check if company_posts table exists, if not create it
    $pdo->exec("CREATE TABLE IF NOT EXISTS company_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        company_id INT NOT NULL,
        category VARCHAR(50) NOT NULL,
        title VARCHAR(200) NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (company_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Insert post
    $stmt = $pdo->prepare("
        INSERT INTO company_posts (company_id, category, title, content) 
        VALUES (?, ?, ?, ?)
    ");
    
    $stmt->execute([$companyId, $category, $title, $content]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Post created successfully',
        'post_id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
