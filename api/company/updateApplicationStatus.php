<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('company')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$applicationId = isset($input['application_id']) ? intval($input['application_id']) : 0;
$newStatus = isset($input['status']) ? trim($input['status']) : '';

if (!$applicationId || !$newStatus) {
    jsonResponse(['error' => 'Application ID and status are required'], 400);
}

$validStatuses = ['pending', 'reviewing', 'accepted', 'rejected'];
if (!in_array($newStatus, $validStatuses)) {
    jsonResponse(['error' => 'Invalid status'], 400);
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

    // Verify the application belongs to this company's job
    $stmt = $pdo->prepare("
        SELECT ja.id 
        FROM job_applications ja
        INNER JOIN jobs j ON ja.job_id = j.id
        WHERE ja.id = ? AND j.company_id = ?
    ");
    $stmt->execute([$applicationId, $company['id']]);
    
    if (!$stmt->fetch()) {
        jsonResponse(['error' => 'Application not found or unauthorized'], 404);
    }

    // Update application status
    $stmt = $pdo->prepare("UPDATE job_applications SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$newStatus, $applicationId]);

    jsonResponse([
        'success' => true,
        'message' => 'Application status updated successfully'
    ]);
    
} catch (Exception $e) {
    error_log("Error updating application status: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to update application status'], 500);
}
?>
