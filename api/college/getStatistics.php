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
        // Return zeros if no college profile
        echo json_encode([
            'success' => true,
            'total_events' => 0,
            'active_events' => 0,
            'total_posts' => 0,
            'total_applications' => 0,
            'pending_applications' => 0
        ]);
        exit;
    }
    
    $college_id = $college['id'];
    
    // Get total events count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM events WHERE college_id = ?");
    $stmt->execute([$college_id]);
    $total_events = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get active events count (status = 'active')
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM events WHERE college_id = ? AND status = 'active'");
    $stmt->execute([$college_id]);
    $active_events = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get total posts count
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM college_posts WHERE college_id = ?");
    $stmt->execute([$college_id]);
    $total_posts = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get total applications count
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM event_applications ea
        INNER JOIN events e ON ea.event_id = e.id
        WHERE e.college_id = ?
    ");
    $stmt->execute([$college_id]);
    $total_applications = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get pending applications count
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM event_applications ea
        INNER JOIN events e ON ea.event_id = e.id
        WHERE e.college_id = ? AND ea.status = 'pending'
    ");
    $stmt->execute([$college_id]);
    $pending_applications = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo json_encode([
        'success' => true,
        'total_events' => (int)$total_events,
        'active_events' => (int)$active_events,
        'total_posts' => (int)$total_posts,
        'total_applications' => (int)$total_applications,
        'pending_applications' => (int)$pending_applications
    ]);
    
} catch (PDOException $e) {
    error_log("Get statistics error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch statistics'
    ]);
}
?>
