<?php
require_once '../../includes/session.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a college
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'college') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['name']) || empty(trim($input['name']))) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'College name is required']);
    exit;
}

$user_id = $_SESSION['user_id'];
$name = trim($input['name']);
$location = isset($input['location']) ? trim($input['location']) : null;
$description = isset($input['description']) ? trim($input['description']) : null;
$website = isset($input['website']) ? trim($input['website']) : null;
$contact_email = isset($input['contact_email']) ? trim($input['contact_email']) : null;
$logo = isset($input['logo']) ? trim($input['logo']) : null;

// Validate email if provided
if ($contact_email && !filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid email format']);
    exit;
}

// Validate URL if provided
if ($website && !filter_var($website, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid website URL']);
    exit;
}

if ($logo && !filter_var($logo, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid logo URL']);
    exit;
}

try {
    // Check if college profile exists
    $stmt = $pdo->prepare("SELECT id FROM colleges WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $college = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($college) {
        // Update existing profile
        $stmt = $pdo->prepare("
            UPDATE colleges 
            SET name = ?, location = ?, description = ?, website = ?, contact_email = ?, logo = ?, updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([$name, $location, $description, $website, $contact_email, $logo, $user_id]);
    } else {
        // Insert new profile
        $stmt = $pdo->prepare("
            INSERT INTO colleges (user_id, name, location, description, website, contact_email, logo, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$user_id, $name, $location, $description, $website, $contact_email, $logo]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully'
    ]);
    
} catch (PDOException $e) {
    error_log("Update college profile error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
?>
