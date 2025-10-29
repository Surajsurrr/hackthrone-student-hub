<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');

// Check if user is logged in
if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
    exit;
}

try {
    $userId = $_SESSION['user_id'];
    $database = Database::getInstance();
    
    // Get student notes
    $notes = $database->fetchAll("SELECT * FROM notes WHERE student_id = ? OR shared_with = 'all' ORDER BY created_at DESC LIMIT 10", [$userId]) ?? [];
    
    jsonResponse(['success' => true, 'notes' => $notes]);
} catch (Exception $e) {
    error_log("Fetch notes error: " . $e->getMessage());
    jsonResponse(['success' => false, 'error' => 'Failed to fetch notes'], 500);
}
?>
