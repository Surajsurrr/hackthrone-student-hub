<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing reminder ID']);
    exit;
}

$reminder_id = $data['id'];
$done = isset($data['done']) ? (bool)$data['done'] : true;

try {
    // Verify the reminder belongs to the user
    $stmt = $pdo->prepare("SELECT id FROM reminders WHERE id = ? AND user_id = ?");
    $stmt->execute([$reminder_id, $user_id]);
    
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Reminder not found']);
        exit;
    }

    // Update the reminder
    $stmt = $pdo->prepare("UPDATE reminders SET done = ? WHERE id = ?");
    $stmt->execute([$done, $reminder_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Reminder updated successfully'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update reminder']);
}
?>
