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
    $conn->close();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    $conn->close();
    exit();
}

$user_id = $_SESSION['user_id'];

// Get JSON data
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$community_name = trim($data['community_name'] ?? '');
$community_subject = trim($data['community_subject'] ?? '');
$community_description = trim($data['community_description'] ?? '');
$community_type = trim($data['community_type'] ?? 'public');
$community_tags = trim($data['community_tags'] ?? '');

// Validate input
if (empty($community_name) || empty($community_subject) || empty($community_description)) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled']);
    $conn->close();
    exit();
}

// Validate type
if (!in_array($community_type, ['public', 'private'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid community type']);
    $conn->close();
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();
    
    // Insert community
    $stmt = $conn->prepare("INSERT INTO communities (name, subject, description, type, tags, creator_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $community_name, $community_subject, $community_description, $community_type, $community_tags, $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to create community');
    }
    
    $community_id = $conn->insert_id;
    
    // Add creator as member with creator role
    $member_stmt = $conn->prepare("INSERT INTO community_members (community_id, user_id, role) VALUES (?, ?, 'creator')");
    $member_stmt->bind_param("ii", $community_id, $user_id);
    
    if (!$member_stmt->execute()) {
        throw new Exception('Failed to add creator as member');
    }
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Community created successfully!',
        'community_id' => $community_id
    ]);
    
    $stmt->close();
    $member_stmt->close();
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    error_log("Error creating community: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to create community']);
}

$conn->close();
?>
