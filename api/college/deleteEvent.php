<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('college')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$eventId = (int)($input['event_id'] ?? 0);

if (!$eventId) {
    jsonResponse(['error' => 'Event ID is required'], 400);
}

$userId = $_SESSION['user_id'];
global $db;

$college = $db->fetchOne("SELECT id FROM colleges WHERE user_id = ?", [$userId]);
if (!$college) {
    jsonResponse(['error' => 'College profile not found'], 404);
}

try {
    $db->delete('events', "id = ? AND college_id = ?", [$eventId, $college['id']]);
    jsonResponse(['success' => true]);
} catch (Exception $e) {
    jsonResponse(['error' => 'Failed to delete event'], 500);
}
?>
