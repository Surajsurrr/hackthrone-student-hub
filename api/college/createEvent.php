<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('college')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];

global $db;
$college = $db->fetchOne("SELECT id FROM colleges WHERE user_id = ?", [$userId]);

if (!$college) {
    jsonResponse(['error' => 'College profile not found'], 404);
}

$data = [
    'college_id' => $college['id'],
    'title' => sanitize($input['title'] ?? ''),
    'description' => sanitize($input['description'] ?? ''),
    'date' => $input['date'] ?? '',
    'type' => sanitize($input['type'] ?? ''),
    'location' => sanitize($input['location'] ?? ''),
    'max_participants' => (int)($input['max_participants'] ?? 0),
    'status' => 'active'
];

if (empty($data['title']) || empty($data['description']) || empty($data['date'])) {
    jsonResponse(['error' => 'Title, description, and date are required'], 400);
}

try {
    $eventId = $db->insert('events', $data);
    jsonResponse(['success' => true, 'event_id' => $eventId], 201);
} catch (Exception $e) {
    jsonResponse(['error' => 'Failed to create event'], 500);
}
?>
