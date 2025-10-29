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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'error' => 'Method not allowed'], 405);
    exit;
}

try {
    $userId = $_SESSION['user_id'];
    $database = Database::getInstance();
    
    $title = trim($_POST['title'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $difficulty = trim($_POST['difficulty'] ?? 'Beginner');
    $tags = trim($_POST['tags'] ?? '');
    $topics = $_POST['topics'] ?? '[]';

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
        INSERT INTO notes (student_id, title, description, subject, topics, tags, difficulty_level, file_path, file_type, file_size, shared_with) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'all')
    ");
    
    $result = $stmt->execute([
        $student['id'], 
        $title, 
        $description, 
        $subject,
        $topics,
        $tags,
        $difficulty,
        'notes/' . $fileName, 
        $fileExt, 
        $file['size']
    ]);

    if ($result) {
        $noteId = $db->lastInsertId();
        
        // Process and save topics
        $topicsArray = json_decode($topics, true);
        if (is_array($topicsArray) && !empty($topicsArray)) {
            $topicStmt = $db->prepare("INSERT INTO note_topics (note_id, topic_id) VALUES (?, ?)");
            foreach ($topicsArray as $topic) {
                if (isset($topic['id'])) {
                    $topicStmt->execute([$noteId, $topic['id']]);
                }
            }
        }
        
        // Process and save tags
        if (!empty($tags)) {
            $tagsArray = array_map('trim', explode(',', $tags));
            $tagStmt = $db->prepare("INSERT IGNORE INTO tags (name) VALUES (?)");
            $noteTagStmt = $db->prepare("INSERT INTO note_tags (note_id, tag_id) VALUES (?, ?)");
            
            foreach ($tagsArray as $tagName) {
                if (!empty($tagName)) {
                    // Insert tag if it doesn't exist
                    $tagStmt->execute([$tagName]);
                    
                    // Get tag ID
                    $getTagStmt = $db->prepare("SELECT id FROM tags WHERE name = ?");
                    $getTagStmt->execute([$tagName]);
                    $tag = $getTagStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($tag) {
                        // Link note to tag
                        $noteTagStmt->execute([$noteId, $tag['id']]);
                        
                        // Update tag usage count
                        $updateTagStmt = $db->prepare("UPDATE tags SET usage_count = usage_count + 1 WHERE id = ?");
                        $updateTagStmt->execute([$tag['id']]);
                    }
                }
            }
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Notes uploaded successfully with topics and tags!',
            'note_id' => $noteId
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save note to database']);
    }

} catch (Exception $e) {
    error_log("Notes upload error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Server error occurred']);
}
?>
