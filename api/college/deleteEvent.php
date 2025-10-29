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
$event_id = (int)($input['event_id'] ?? 0);

if (!$event_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Event ID is required']);
    exit;
}

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
    
    // Delete event (only if it belongs to this college)
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ? AND college_id = ?");
    $stmt->execute([$event_id, $college['id']]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Event not found or unauthorized']);
    }
    
} catch (PDOException $e) {
    error_log("Delete event error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
?>
