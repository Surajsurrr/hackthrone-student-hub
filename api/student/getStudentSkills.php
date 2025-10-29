<?php
require_once '../../includes/session.php';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'skills' => [], 'message' => 'Database connection failed']);
    exit();
}

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn() || !hasRole('student')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$student_id = intval($_GET['student_id'] ?? 0);

// Validate input
if (empty($student_id)) {
    echo json_encode(['success' => false, 'skills' => []]);
    exit();
}

try {
    // Get student's skills
    $stmt = $conn->prepare("SELECT DISTINCT skill_name FROM student_skills WHERE student_id = ? ORDER BY skill_name");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $skills = [];
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row['skill_name'];
    }
    
    echo json_encode([
        'success' => true,
        'skills' => $skills
    ]);
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching student skills: " . $e->getMessage());
    echo json_encode(['success' => false, 'skills' => []]);
}

$conn->close();
?>
