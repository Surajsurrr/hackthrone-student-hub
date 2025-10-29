<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$userId = $_SESSION['user_id'];

if (empty($_FILES['profile_pic'])) {
    jsonResponse(['success' => false, 'message' => 'No file uploaded'], 400);
}

$file = $_FILES['profile_pic'];

// Validate upload
$allowed = ['jpg','jpeg','png','gif'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed)) {
    jsonResponse(['success' => false, 'message' => 'Invalid file type'], 400);
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    jsonResponse(['success' => false, 'message' => 'Upload error code: ' . $file['error']], 400);
}

if ($file['size'] > MAX_FILE_SIZE) {
    jsonResponse(['success' => false, 'message' => 'File too large'], 400);
}

// Ensure upload directory exists
// Resolve absolute upload directory based on project root to avoid relative-path issues
$projectRoot = realpath(__DIR__ . '/../../..');
if ($projectRoot === false) {
    jsonResponse(['success' => false, 'message' => 'Server configuration error (project root)'], 500);
}

$absUploadBase = $projectRoot . DIRECTORY_SEPARATOR . trim(UPLOAD_DIR, '\\/');
$targetDir = $absUploadBase . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
if (!is_dir($targetDir)) {
    if (!@mkdir($targetDir, 0755, true)) {
        jsonResponse(['success' => false, 'message' => 'Failed to create upload directory'], 500);
    }
}

$newName = uniqid('prof_') . '.' . $ext;
$targetPath = $targetDir . $newName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    jsonResponse(['success' => false, 'message' => 'Failed to move uploaded file'], 500);
}

// Store relative path in DB (uploads/profile_pics/filename)
$storedPath = rtrim(UPLOAD_DIR, '/') . '/profile_pics/' . $newName;

try {
    global $db;
    $stmt = $db->prepare("UPDATE students SET profile_pic = ? WHERE user_id = ?");
    $stmt->execute([$storedPath, $userId]);

    // Return a path usable from the student dashboard (student/ is one level down)
    $imageUrl = '../' . $storedPath;
    jsonResponse(['success' => true, 'image_url' => $imageUrl]);
} catch (Exception $e) {
    // cleanup file
    @unlink($targetPath);
    jsonResponse(['success' => false, 'message' => 'Failed to save profile reference'], 500);
}

?>
