<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
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

try {
    $stmt = $pdo->prepare("
        SELECT id, text, due_at, done, created_at
        FROM reminders
        WHERE user_id = ?
        ORDER BY done ASC, due_at ASC, created_at DESC
    ");
    $stmt->execute([$user_id]);
    $reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the reminders for frontend
    $formatted_reminders = array_map(function($reminder) {
        return [
            'id' => $reminder['id'],
            'text' => $reminder['text'],
            'due_at' => $reminder['due_at'],
            'done' => (bool)$reminder['done'],
            'created_at' => $reminder['created_at'],
            'is_overdue' => $reminder['due_at'] && strtotime($reminder['due_at']) < time() && !$reminder['done']
        ];
    }, $reminders);

    echo json_encode([
        'success' => true,
        'reminders' => $formatted_reminders
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch reminders']);
}
?>