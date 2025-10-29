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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    $userId = $_SESSION['user_id'];
    $title = trim($_POST['title'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validate required fields
    if (empty($title)) {
        echo json_encode(['success' => false, 'error' => 'Title is required']);
        exit;
    }

    if (empty($subject)) {
        echo json_encode(['success' => false, 'error' => 'Subject is required']);
        exit;
    }

    // Check if file was uploaded
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'Please select a file to upload']);
        exit;
    }

    $file = $_FILES['file'];
    
    // Validate file size (10MB limit)
    $maxSize = 10 * 1024 * 1024; // 10MB in bytes
    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'error' => 'File size must be less than 10MB']);
        exit;
    }

    // Validate file type
    $allowedTypes = ['pdf', 'doc', 'docx', 'txt', 'ppt', 'pptx'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileExt, $allowedTypes)) {
        echo json_encode(['success' => false, 'error' => 'Invalid file type. Allowed: PDF, DOC, DOCX, TXT, PPT, PPTX']);
        exit;
    }

    // Create upload directory if it doesn't exist
    $uploadDir = '../../uploads/notes/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate unique filename
    $fileName = time() . '_' . uniqid() . '.' . $fileExt;
    $filePath = $uploadDir . $fileName;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
        exit;
    }

    // Get student ID from users table
    $stmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
    $stmt->execute([$userId]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(['success' => false, 'error' => 'Student profile not found']);
        exit;
    }

    // Add subject column if it doesn't exist
    try {
        $db->exec("ALTER TABLE notes ADD COLUMN subject VARCHAR(100) DEFAULT 'General' AFTER description");
    } catch (PDOException $e) {
        // Column probably already exists, continue
    }

    // Add likes column if it doesn't exist
    try {
        $db->exec("ALTER TABLE notes ADD COLUMN likes INT DEFAULT 0 AFTER downloads");
    } catch (PDOException $e) {
        // Column probably already exists, continue
    }

    // Insert note into database
    $stmt = $db->prepare("
        INSERT INTO notes (student_id, title, description, subject, file_path, file_type, file_size, shared_with) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'all')
    ");
    
    $result = $stmt->execute([
        $student['id'], 
        $title, 
        $description, 
        $subject,
        'notes/' . $fileName, 
        $fileExt, 
        $file['size']
    ]);

    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Notes uploaded successfully!',
            'note_id' => $db->lastInsertId()
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save note to database']);
    }

} catch (Exception $e) {
    error_log("Notes upload error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Server error occurred']);
}
?>
