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

    $title = sanitize($input['title'] ?? '');
    $description = sanitize($input['description'] ?? '');
    $requirements = sanitize($input['requirements'] ?? '');
    $salary = sanitize($input['salary'] ?? '');
    $type = sanitize($input['type'] ?? '');

    if (empty($title) || empty($description) || empty($requirements)) {
        jsonResponse(['error' => 'Title, description, and requirements are required'], 400);
    }

    // Insert job
    $stmt = $pdo->prepare("
        INSERT INTO jobs (company_id, title, description, requirements, salary, type, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())
    ");
    
    $stmt->execute([
        $company['id'],
        $title,
        $description,
        $requirements,
        $salary,
        $type
    ]);

    $jobId = $pdo->lastInsertId();
    jsonResponse(['success' => true, 'job_id' => $jobId], 201);
    
} catch (Exception $e) {
    error_log("Error creating job: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to create job: ' . $e->getMessage()], 500);
}
?>
