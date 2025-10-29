<?php
// Start session first so functions that rely on $_SESSION work correctly
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    jsonResponse(['success' => false, 'message' => 'Invalid JSON data'], 400);
}

// Validate required fields
$required = ['title', 'type', 'company', 'platform'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        jsonResponse(['success' => false, 'message' => "Field '$field' is required"], 400);
    }
}

$data = [
    'user_id' => $userId,
    'type' => sanitize($input['type']),
    'title' => sanitize($input['title']),
    'company_or_org' => sanitize($input['company']),
    'platform' => sanitize($input['platform']),
    'application_link' => sanitize($input['link'] ?? ''),
    'location' => sanitize($input['location'] ?? ''),
    'deadline' => !empty($input['deadline']) ? $input['deadline'] : null,
    'notes' => sanitize($input['notes'] ?? ''),
    'status' => 'applied',
    'applied_at' => date('Y-m-d H:i:s')
];

try {
    global $db;
    
    // Insert into applications table
    $sql = "INSERT INTO applications (user_id, type, title, company_or_org, platform, application_link, location, deadline, notes, status, applied_at) 
            VALUES (:user_id, :type, :title, :company_or_org, :platform, :application_link, :location, :deadline, :notes, :status, :applied_at)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($data);
    
    jsonResponse(['success' => true, 'message' => 'Application added successfully']);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to add application: ' . $e->getMessage()], 500);
}
?>