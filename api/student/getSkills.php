<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/config.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed', 'skills' => []]);
    exit;
}

header('Content-Type: application/json');

// Check authentication
if (!isLoggedIn() || !hasRole('student')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$student_id = $_SESSION['user_id'];

try {
    // Get skills with endorsement counts
    $query = "SELECT 
                s.id,
                s.skill_name,
                s.skill_level,
                s.created_at,
                COUNT(e.id) as endorsement_count
              FROM student_skills s
              LEFT JOIN endorsements e ON s.skill_name = e.skill_name AND e.endorsed_id = ?
              WHERE s.student_id = ?
              GROUP BY s.id
              ORDER BY endorsement_count DESC, s.created_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $student_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $skills = [];
    while ($row = $result->fetch_assoc()) {
        $skills[] = [
            'id' => $row['id'],
            'skill_name' => $row['skill_name'],
            'skill_level' => $row['skill_level'],
            'endorsement_count' => (int)$row['endorsement_count'],
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'skills' => $skills
    ]);
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching skills: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error',
        'skills' => []
    ]);
}

$conn->close();
?>