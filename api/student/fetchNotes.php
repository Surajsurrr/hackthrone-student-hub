<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');

require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    $userId = $_SESSION['user_id'];
    $notes = getStudentNotes($userId);
    
    echo json_encode(['success' => true, 'notes' => $notes]);
} catch (Exception $e) {
    error_log("Fetch notes error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Failed to fetch notes']);
}
?>
