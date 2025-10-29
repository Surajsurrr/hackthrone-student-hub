<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];

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
