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

$student_id = $_SESSION['user_id'];
$skill_name = trim($_POST['skill_name'] ?? '');
$skill_level = trim($_POST['skill_level'] ?? '');

// Validate input
if (empty($skill_name) || empty($skill_level)) {
    echo json_encode(['success' => false, 'message' => 'Skill name and level are required']);
    exit();
}

// Validate skill level
$valid_levels = ['beginner', 'intermediate', 'advanced', 'expert'];
if (!in_array($skill_level, $valid_levels)) {
    echo json_encode(['success' => false, 'message' => 'Invalid skill level']);
    exit();
}

try {
    // Check if skill already exists
    $check_stmt = $conn->prepare("SELECT id FROM student_skills WHERE student_id = ? AND skill_name = ?");
    $check_stmt->bind_param("is", $student_id, $skill_name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You already have this skill']);
        exit();
    }
    
    // Insert new skill
    $stmt = $conn->prepare("INSERT INTO student_skills (student_id, skill_name, skill_level) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $student_id, $skill_name, $skill_level);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Skill added successfully',
            'skill_id' => $conn->insert_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add skill']);
    }
    
    $stmt->close();
    $check_stmt->close();
} catch (Exception $e) {
    error_log("Error adding skill: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}

$conn->close();
?>
