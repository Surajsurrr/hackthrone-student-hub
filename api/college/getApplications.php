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
    
    // Get all events for this college
    $stmt = $pdo->prepare("
        SELECT id, title, date, type 
        FROM events 
        WHERE college_id = ? 
        ORDER BY date DESC
    ");
    $stmt->execute([$college_id]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get all applications for this college's events
    $stmt = $pdo->prepare("
        SELECT 
            ea.*,
            e.title as event_title,
            e.type as event_type,
            e.date as event_date
        FROM event_applications ea
        INNER JOIN events e ON ea.event_id = e.id
        WHERE e.college_id = ?
        ORDER BY ea.applied_at DESC
    ");
    $stmt->execute([$college_id]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'applications' => $applications,
        'events' => $events,
        'total_count' => count($applications),
        'pending_count' => count(array_filter($applications, fn($a) => $a['status'] === 'pending')),
        'approved_count' => count(array_filter($applications, fn($a) => $a['status'] === 'approved')),
        'rejected_count' => count(array_filter($applications, fn($a) => $a['status'] === 'rejected'))
    ]);
    
} catch (PDOException $e) {
    error_log("Get applications error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch applications'
    ]);
}
?>
