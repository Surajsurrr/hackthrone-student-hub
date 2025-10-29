<?php
require_once '../../includes/session.php';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'students' => [], 'message' => 'Database connection failed']);
    exit();
}

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn() || !hasRole('student')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    $conn->close();
    exit();
}

$search_term = trim($_GET['search'] ?? '');

// Validate input
if (empty($search_term)) {
    echo json_encode(['success' => false, 'students' => []]);
    $conn->close();
    exit();
}

$current_user_id = $_SESSION['user_id'];

try {
    // Search for students (excluding current user)
    $search_like = "%{$search_term}%";
    $stmt = $conn->prepare("
        SELECT u.id, u.name, u.email, s.college, s.course
        FROM users u
        LEFT JOIN students s ON u.id = s.user_id
        WHERE u.role = 'student' 
        AND u.id != ?
        AND (u.name LIKE ? OR u.email LIKE ?)
        LIMIT 10
    ");
    $stmt->bind_param("iss", $current_user_id, $search_like, $search_like);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'college' => $row['college'] ?? 'Not specified',
            'course' => $row['course'] ?? 'Not specified'
        ];
    }
    
    echo json_encode([
        'success' => true,
        'students' => $students
    ]);
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error searching students: " . $e->getMessage());
    echo json_encode(['success' => false, 'students' => []]);
}

$conn->close();
?>
