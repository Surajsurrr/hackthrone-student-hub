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

// Support both JSON payloads and form-data (FormData)
$userId = $_SESSION['user_id'];
$input = [];
if (!empty($_POST)) {
    $input = $_POST;
} else {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $input = $decoded;
    }
}

$data = [
    'name' => sanitize($input['name'] ?? ''),
    'college' => sanitize($input['college'] ?? ''),
    'year' => sanitize($input['year'] ?? ''),
    'branch' => sanitize($input['branch'] ?? ''),
    'skills' => sanitize($input['skills'] ?? ''),
    'bio' => sanitize($input['bio'] ?? '')
];

try {
    updateStudentProfile($userId, $data);
    jsonResponse(['success' => true]);
} catch (Exception $e) {
    // Include exception message for debugging
    $msg = 'Failed to update profile: ' . $e->getMessage();
    jsonResponse(['success' => false, 'message' => $msg], 500);
}
?>
