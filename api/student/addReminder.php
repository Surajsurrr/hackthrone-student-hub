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

if (!$data || !isset($data['text'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$text = trim($data['text']);
$due_at = isset($data['due_at']) ? $data['due_at'] : null;

if (empty($text)) {
    http_response_code(400);
    echo json_encode(['error' => 'Reminder text cannot be empty']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO reminders (user_id, text, due_at) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $text, $due_at]);

    echo json_encode([
        'success' => true,
        'message' => 'Reminder added successfully',
        'id' => $pdo->lastInsertId()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add reminder']);
}
?>