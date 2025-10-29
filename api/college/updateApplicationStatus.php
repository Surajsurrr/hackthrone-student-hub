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

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$application_id = isset($input['application_id']) ? intval($input['application_id']) : 0;
$new_status = isset($input['status']) ? $input['status'] : '';

if (!$application_id || !in_array($new_status, ['pending', 'approved', 'rejected'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
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
    
    // Verify that the application belongs to this college's event
    $stmt = $pdo->prepare("
        SELECT ea.id 
        FROM event_applications ea
        INNER JOIN events e ON ea.event_id = e.id
        WHERE ea.id = ? AND e.college_id = ?
    ");
    $stmt->execute([$application_id, $college_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$application) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Application not found or unauthorized']);
        exit;
    }
    
    // Update application status
    $stmt = $pdo->prepare("
        UPDATE event_applications 
        SET status = ?, updated_at = NOW() 
        WHERE id = ?
    ");
    $stmt->execute([$new_status, $application_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Application status updated successfully'
    ]);
    
} catch (PDOException $e) {
    error_log("Update application status error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to update application status'
    ]);
}
?>
