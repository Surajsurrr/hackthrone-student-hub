<?php
require_once '../../includes/session.php';
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'communities' => [], 'message' => 'Database connection failed']);
    exit();
}

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn() || !hasRole('student')) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized', 'communities' => []]);
    $conn->close();
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Get communities the user is a member of
    $query = "SELECT 
                c.id,
                c.name,
                c.subject,
                c.description,
                c.type,
                c.member_count,
                c.post_count,
                c.created_at,
                cm.role,
                cm.joined_at
              FROM communities c
              INNER JOIN community_members cm ON c.id = cm.community_id
              WHERE cm.user_id = ?
              ORDER BY cm.joined_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $communities = [];
    while ($row = $result->fetch_assoc()) {
        $communities[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'subject' => $row['subject'],
            'description' => $row['description'],
            'type' => $row['type'],
            'member_count' => (int)$row['member_count'],
            'post_count' => (int)$row['post_count'],
            'role' => $row['role'],
            'created_at' => $row['created_at'],
            'joined_at' => $row['joined_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'communities' => $communities
    ]);
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching my communities: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'communities' => [],
        'message' => 'Failed to load communities'
    ]);
}

$conn->close();
?>
