<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
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
    'skills' => sanitize($input['skills'] ?? '')
];

try {
    updateStudentProfile($userId, $data);
    jsonResponse(['success' => true]);
} catch (Exception $e) {
    jsonResponse(['error' => 'Failed to update profile'], 500);
}
?>
