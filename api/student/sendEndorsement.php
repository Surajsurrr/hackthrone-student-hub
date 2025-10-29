<?php
require_once '../../includes/session.php';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn() || !hasRole('student')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$endorser_id = $_SESSION['user_id'];
$endorsed_id = intval($_POST['endorsed_id'] ?? 0);
$skill_name = trim($_POST['skill_name'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate input
if (empty($endorsed_id) || empty($skill_name) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

// Check if user is trying to endorse themselves
if ($endorser_id === $endorsed_id) {
    echo json_encode(['success' => false, 'message' => 'You cannot endorse yourself']);
    exit();
}

// Validate message length
if (strlen($message) > 500) {
    echo json_encode(['success' => false, 'message' => 'Message is too long (max 500 characters)']);
    exit();
}

try {
    // Check if endorsed user exists and has the skill
    $check_stmt = $conn->prepare("SELECT id FROM student_skills WHERE student_id = ? AND skill_name = ?");
    $check_stmt->bind_param("is", $endorsed_id, $skill_name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'User does not have this skill']);
        exit();
    }
    
    // Check if already endorsed this skill for this user
    $dup_check = $conn->prepare("SELECT id FROM endorsements WHERE endorser_id = ? AND endorsed_id = ? AND skill_name = ?");
    $dup_check->bind_param("iis", $endorser_id, $endorsed_id, $skill_name);
    $dup_check->execute();
    $dup_result = $dup_check->get_result();
    
    if ($dup_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You have already endorsed this skill for this user']);
        exit();
    }
    
    // Insert endorsement
    $stmt = $conn->prepare("INSERT INTO endorsements (endorser_id, endorsed_id, skill_name, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $endorser_id, $endorsed_id, $skill_name, $message);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Endorsement sent successfully!',
            'endorsement_id' => $conn->insert_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send endorsement']);
    }
    
    $stmt->close();
    $check_stmt->close();
    $dup_check->close();
} catch (Exception $e) {
    error_log("Error sending endorsement: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}

$conn->close();
?>
