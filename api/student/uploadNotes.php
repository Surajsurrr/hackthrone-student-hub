<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$userId = $_SESSION['user_id'];
$title = sanitize($_POST['title'] ?? '');
$description = sanitize($_POST['description'] ?? '');

if (empty($title)) {
    jsonResponse(['error' => 'Title is required'], 400);
}

$uploadResult = uploadFile($_FILES['file'] ?? null);
if (!$uploadResult['success']) {
    jsonResponse(['error' => $uploadResult['message']], 400);
}

try {
    global $db;
    $noteId = $db->insert('notes', [
        'student_id' => $userId,
        'title' => $title,
        'description' => $description,
        'file_path' => $uploadResult['file'],
        'shared_with' => 'all'
    ]);

    jsonResponse(['success' => true, 'note_id' => $noteId], 201);
} catch (Exception $e) {
    jsonResponse(['error' => 'Failed to upload notes'], 500);
}
?>
