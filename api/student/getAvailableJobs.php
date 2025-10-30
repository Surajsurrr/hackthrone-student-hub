<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Get all active jobs with company information
    $stmt = $pdo->prepare("
        SELECT 
            j.id,
            j.title,
            j.description,
            j.requirements,
            j.salary,
            j.type,
            j.created_at,
            c.id as company_id,
            u.username as company_name
        FROM jobs j
        INNER JOIN companies c ON j.company_id = c.id
        INNER JOIN users u ON c.user_id = u.id
        WHERE j.status = 'active'
        ORDER BY j.created_at DESC
    ");
    
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(['success' => true, 'jobs' => $jobs]);
    
} catch (Exception $e) {
    error_log("Error fetching available jobs: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to fetch jobs'], 500);
}
?>
