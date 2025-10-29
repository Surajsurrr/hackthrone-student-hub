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

// Check if file was uploaded
if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
    exit;
}

$file = $_FILES['document'];
$allowedTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'text/plain'
];
$maxFileSize = 10 * 1024 * 1024; // 10MB

// Validate file type
if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Only PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, and TXT files are allowed']);
    exit;
}

// Validate file size
if ($file['size'] > $maxFileSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'File too large. Maximum size is 10MB']);
    exit;
}

// Create upload directory if it doesn't exist
$uploadDir = '../../uploads/college_posts/documents/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$originalName = pathinfo($file['name'], PATHINFO_FILENAME);
$filename = uniqid('doc_', true) . '_' . time() . '.' . $extension;
$filepath = $uploadDir . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Return the web-accessible path
    $webPath = '../uploads/college_posts/documents/' . $filename;
    
    echo json_encode([
        'success' => true,
        'file_path' => $webPath,
        'filename' => $filename,
        'original_name' => $file['name'],
        'file_size' => $file['size']
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save file']);
}
?>
