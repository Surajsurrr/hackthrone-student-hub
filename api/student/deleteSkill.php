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
$skill_id = intval($_POST['skill_id'] ?? 0);

// Validate input
if (empty($skill_id)) {
    echo json_encode(['success' => false, 'message' => 'Skill ID is required']);
    exit();
}

try {
    // Delete skill (only if it belongs to the current user)
    $stmt = $conn->prepare("DELETE FROM student_skills WHERE id = ? AND student_id = ?");
    $stmt->bind_param("ii", $skill_id, $student_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Skill deleted successfully'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Skill not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete skill']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error deleting skill: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}

$conn->close();
?>
