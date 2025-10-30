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

    // Get jobs for this company
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
    $stmt->execute([$company['id']]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(['jobs' => $jobs]);
    
} catch (Exception $e) {
    error_log("Error fetching jobs: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to fetch jobs'], 500);
}
?>
