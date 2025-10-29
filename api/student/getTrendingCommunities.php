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

try {
    // Get trending communities (public communities with most members, limited to 5)
    $query = "SELECT 
                c.id,
                c.name,
                c.subject,
                c.description,
                c.type,
                c.member_count,
                c.post_count,
                c.created_at
              FROM communities c
              WHERE c.type = 'public'
              ORDER BY c.member_count DESC, c.created_at DESC
              LIMIT 5";
    
    $result = $conn->query($query);
    
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
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'communities' => $communities
    ]);
    
} catch (Exception $e) {
    error_log("Error fetching trending communities: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'communities' => [],
        'message' => 'Failed to load communities'
    ]);
}

$conn->close();
?>
