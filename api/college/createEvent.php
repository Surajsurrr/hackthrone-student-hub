<?php
require_once '../../includes/session.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a college
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'college') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];

try {
    // Get college ID
    $stmt = $pdo->prepare("SELECT id FROM colleges WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $college = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$college) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'College profile not found']);
        exit;
    }
    
    $college_id = $college['id'];
    $title = trim($input['title'] ?? '');
    $description = trim($input['description'] ?? '');
    $date = $input['date'] ?? '';
    $type = $input['type'] ?? 'other';
    $location = trim($input['location'] ?? '');
    $max_participants = (int)($input['max_participants'] ?? 0);
    
    // Validate required fields
    if (empty($title) || empty($description) || empty($date)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Title, description, and date are required']);
        exit;
    }
    
    // Insert event
    $stmt = $pdo->prepare("
        INSERT INTO events (college_id, title, description, date, type, location, max_participants, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'active', NOW(), NOW())
    ");
    $stmt->execute([$college_id, $title, $description, $date, $type, $location, $max_participants]);
    
    $event_id = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'event_id' => $event_id,
        'message' => 'Event created successfully'
    ]);
    
} catch (PDOException $e) {
    error_log("Create event error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
?>
