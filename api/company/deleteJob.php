<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('company')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$jobId = (int)($input['job_id'] ?? 0);

if (!$jobId) {
    jsonResponse(['error' => 'Job ID is required'], 400);
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

    // Delete job
    $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ? AND company_id = ?");
    $stmt->execute([$jobId, $company['id']]);

    if ($stmt->rowCount() > 0) {
        jsonResponse(['success' => true]);
    } else {
        jsonResponse(['error' => 'Job not found or already deleted'], 404);
    }
    
} catch (Exception $e) {
    error_log("Error deleting job: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to delete job'], 500);
}
?>
