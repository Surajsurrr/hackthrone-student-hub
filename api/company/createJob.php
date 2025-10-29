<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('company')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];

global $db;
$company = $db->fetchOne("SELECT id FROM companies WHERE user_id = ?", [$userId]);

if (!$company) {
    jsonResponse(['error' => 'Company profile not found'], 404);
}

$data = [
    'company_id' => $company['id'],
    'title' => sanitize($input['title'] ?? ''),
    'description' => sanitize($input['description'] ?? ''),
    'requirements' => sanitize($input['requirements'] ?? ''),
    'salary' => sanitize($input['salary'] ?? ''),
    'location' => sanitize($input['location'] ?? ''),
    'type' => sanitize($input['type'] ?? ''),
    'application_deadline' => $input['application_deadline'] ?? null,
    'status' => 'active'
];

if (empty($data['title']) || empty($data['description']) || empty($data['requirements'])) {
    jsonResponse(['error' => 'Title, description, and requirements are required'], 400);
}

try {
    $jobId = $db->insert('jobs', $data);
    jsonResponse(['success' => true, 'job_id' => $jobId], 201);
} catch (Exception $e) {
    jsonResponse(['error' => 'Failed to create job'], 500);
}
?>
