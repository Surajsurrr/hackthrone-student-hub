<?php
require_once '../../includes/config.php';
require_once '../../includes/session.php';

header('Content-Type: application/json');

// Check if user is logged in and is a company
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

$companyId = $_SESSION['user_id'];

try {
    // Check if company_posts table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'company_posts'");
    if ($tableCheck->rowCount() === 0) {
        echo json_encode(['success' => true, 'posts' => []]);
        exit;
    }

    // Get posts with company information
    $stmt = $pdo->prepare("
        SELECT 
            cp.*,
            c.name as company_name,
            c.logo as company_logo
        FROM company_posts cp
        LEFT JOIN companies c ON cp.company_id = c.user_id
        WHERE cp.company_id = ?
        ORDER BY cp.created_at DESC
    ");
    
    $stmt->execute([$companyId]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'posts' => $posts
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
