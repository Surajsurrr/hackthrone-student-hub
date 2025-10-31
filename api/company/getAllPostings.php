<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('company')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

$userId = $_SESSION['user_id'];

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Get company profile
    $stmt = $pdo->prepare("SELECT id, name FROM companies WHERE user_id = ?");
    $stmt->execute([$userId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        jsonResponse(['error' => 'Company profile not found'], 404);
    }

    $companyId = $company['id'];
    
    // Get all jobs
    $stmt = $pdo->prepare("
        SELECT 
            id,
            title,
            description,
            requirements,
            salary,
            location,
            type,
            application_deadline,
            status,
            created_at,
            'job' as posting_type
        FROM jobs 
        WHERE company_id = ? 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$companyId]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get all company posts (if table exists)
    $posts = [];
    try {
        $stmt = $pdo->prepare("
            SELECT 
                id,
                title,
                content as description,
                post_type,
                image_url,
                tags,
                views,
                likes,
                status,
                published_at,
                created_at,
                'post' as posting_type
            FROM company_posts 
            WHERE company_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$companyId]);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Table might not exist yet, continue without posts
        error_log("Company posts table not available: " . $e->getMessage());
    }
    
    // Combine and sort all postings
    $allPostings = array_merge($jobs, $posts);
    
    // Sort by created_at descending
    usort($allPostings, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    // Calculate statistics
    $stats = [
        'total_postings' => count($allPostings),
        'active_jobs' => count(array_filter($jobs, function($job) {
            return $job['status'] === 'active';
        })),
        'total_jobs' => count($jobs),
        'total_posts' => count($posts),
        'published_posts' => count(array_filter($posts, function($post) {
            return $post['status'] === 'published';
        }))
    ];

    jsonResponse([
        'success' => true,
        'postings' => $allPostings,
        'stats' => $stats,
        'company' => $company
    ]);
    
} catch (Exception $e) {
    error_log("Error fetching company postings: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to fetch postings: ' . $e->getMessage()], 500);
}
?>
