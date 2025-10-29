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
$query = sanitize($input['query'] ?? '');

if (empty($query)) {
    jsonResponse(['error' => 'Query is required'], 400);
}

$userId = $_SESSION['user_id'];

// Call AI model endpoint
$aiResponse = file_get_contents(AI_MODEL_ENDPOINT . '?query=' . urlencode($query));

if ($aiResponse === false) {
    jsonResponse(['error' => 'AI service unavailable'], 503);
}

$response = json_decode($aiResponse, true);

// Store conversation
global $db;
$db->insert('ai_responses', [
    'student_id' => $userId,
    'query' => $query,
    'response' => $response['response'] ?? 'No response'
]);

jsonResponse(['response' => $response['response'] ?? 'No response']);
?>
