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
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$userId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$company) {
        jsonResponse(['error' => 'Company profile not found'], 404);
    }

    // Get all applications for this company's jobs
    $stmt = $pdo->prepare("
        SELECT 
            ja.id,
            ja.resume_path,
            ja.cover_letter,
            ja.status,
            ja.applied_at,
            j.title as job_title,
            j.type as job_type,
            u.username as student_name,
            u.email as student_email
        FROM job_applications ja
        INNER JOIN jobs j ON ja.job_id = j.id
        INNER JOIN users u ON ja.user_id = u.id
        WHERE j.company_id = ?
        ORDER BY ja.applied_at DESC
    ");
    
    $stmt->execute([$company['id']]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate stats
    $stats = [
        'total' => count($applications),
        'pending' => 0,
        'reviewing' => 0,
        'accepted' => 0,
        'rejected' => 0
    ];

    foreach ($applications as $app) {
        $status = strtolower($app['status']);
        if (isset($stats[$status])) {
            $stats[$status]++;
        }
    }

    jsonResponse([
        'success' => true,
        'applications' => $applications,
        'stats' => $stats
    ]);
    
} catch (Exception $e) {
    error_log("Error fetching applications: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to fetch applications'], 500);
}
?>
