<?php
require_once '../../includes/session.php';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'endorsements' => [], 'message' => 'Database connection failed']);
    exit();
}

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn() || !hasRole('student')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$student_id = $_SESSION['user_id'];

try {
    // Get endorsements received
    $stmt = $conn->prepare("
        SELECT 
            e.*,
            u.name as endorser_name,
            s.college,
            s.course
        FROM endorsements e
        JOIN users u ON e.endorser_id = u.id
        LEFT JOIN students s ON u.id = s.user_id
        WHERE e.endorsed_id = ?
        ORDER BY e.created_at DESC
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $endorsements = [];
    while ($row = $result->fetch_assoc()) {
        $endorsements[] = [
            'id' => $row['id'],
            'endorser_name' => $row['endorser_name'],
            'skill_name' => $row['skill_name'],
            'message' => $row['message'],
            'college' => $row['college'] ?? 'Not specified',
            'course' => $row['course'] ?? 'Not specified',
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'endorsements' => $endorsements
    ]);
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching endorsements: " . $e->getMessage());
    echo json_encode(['success' => false, 'endorsements' => []]);
}

$conn->close();
?>
