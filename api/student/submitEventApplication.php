<?php
require_once '../../includes/session.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$student_id = $_SESSION['user_id'];
$event_id = isset($input['event_id']) ? intval($input['event_id']) : 0;

if (!$event_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Event ID is required']);
    exit;
}

// Validate required fields
$required_fields = ['full_name', 'email', 'phone', 'college', 'year_of_study', 'branch', 'motivation'];
foreach ($required_fields as $field) {
    if (!isset($input[$field]) || empty(trim($input[$field]))) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit;
    }
}

try {
    // Check if event exists and is active
    $stmt = $pdo->prepare("SELECT id, status FROM events WHERE id = ? AND status = 'active'");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$event) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Event not found or no longer accepting applications']);
        exit;
    }
    
    // Check if student has already applied
    $stmt = $pdo->prepare("SELECT id FROM event_applications WHERE event_id = ? AND student_id = ?");
    $stmt->execute([$event_id, $student_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'You have already applied for this event']);
        exit;
    }
    
    // Insert application
    $stmt = $pdo->prepare("
        INSERT INTO event_applications (
            event_id, student_id, full_name, email, phone, college, 
            year_of_study, branch, team_name, team_size, motivation, 
            experience, skills, github, linkedin, status, applied_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    
    $stmt->execute([
        $event_id,
        $student_id,
        trim($input['full_name']),
        trim($input['email']),
        trim($input['phone']),
        trim($input['college']),
        $input['year_of_study'],
        trim($input['branch']),
        isset($input['team_name']) ? trim($input['team_name']) : null,
        isset($input['team_size']) ? intval($input['team_size']) : 1,
        trim($input['motivation']),
        isset($input['experience']) ? trim($input['experience']) : null,
        isset($input['skills']) ? trim($input['skills']) : null,
        isset($input['github']) ? trim($input['github']) : null,
        isset($input['linkedin']) ? trim($input['linkedin']) : null
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Application submitted successfully',
        'application_id' => $pdo->lastInsertId()
    ]);
    
} catch (PDOException $e) {
    error_log("Submit event application error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to submit application. Please try again.'
    ]);
}
?>
