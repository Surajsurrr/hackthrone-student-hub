<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('company')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$jobId = (int)($input['job_id'] ?? 0);

if (!$jobId) {
    jsonResponse(['error' => 'Job ID is required'], 400);
}

$userId = $_SESSION['user_id'];
global $db;

$company = $db->fetchOne("SELECT id FROM companies WHERE user_id = ?", [$userId]);
if (!$company) {
    jsonResponse(['error' => 'Company profile not found'], 404);
}

try {
    $db->delete('jobs', "id = ? AND company_id = ?", [$jobId, $company['id']]);
    jsonResponse(['success' => true]);
} catch (Exception $e) {
    jsonResponse(['error' => 'Failed to delete job'], 500);
}
?>
