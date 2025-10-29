<?php
require_once __DIR__ . '/db_connect.php';

// Sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    try {
        $database = Database::getInstance();
        $user = $database->fetchOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
        return $user;
    } catch (Exception $e) {
        error_log("Error getting current user: " . $e->getMessage());
        return null;
    }
}

// Check user role
function hasRole($role) {
    try {
        $user = getCurrentUser();
        return $user && $user['role'] === $role;
    } catch (Exception $e) {
        error_log("Error checking user role: " . $e->getMessage());
        return false;
    }
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        // Debug information
        if (defined('DEBUG') && DEBUG) {
            error_log("User not logged in. Session data: " . print_r($_SESSION, true));
        }
        
        // Simple relative redirect
        header('Location: ../login.php');
        exit;
    }
}

// Redirect if not authorized
function requireRole($role) {
    requireLogin();
    
    if (!hasRole($role)) {
        // Debug information
        if (defined('DEBUG') && DEBUG) {
            $currentUser = getCurrentUser();
            error_log("User role mismatch. Required: $role, Current user: " . print_r($currentUser, true));
        }
        
        // Simple relative redirect
        header('Location: ../index.php');
        exit;
    }
}

// Upload file
function uploadFile($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error'];
    }

    $fileName = basename($file['name']);
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }

    if ($fileSize > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'File too large'];
    }

    $newFileName = uniqid() . '.' . $fileType;
    $uploadPath = UPLOAD_DIR . $newFileName;

    if (move_uploaded_file($fileTmp, $uploadPath)) {
        return ['success' => true, 'file' => $newFileName];
    } else {
        return ['success' => false, 'message' => 'Upload failed'];
    }
}

// Format date
function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

// Get user profile picture
function getProfilePic($userId) {
    $picPath = UPLOAD_DIR . 'profile_pics/' . $userId . '.jpg';
    return file_exists($picPath) ? $picPath : 'assets/images/profile_pics/default.jpg';
}

// Send JSON response
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Get events for college
function getCollegeEvents($collegeId) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM events WHERE college_id = ? ORDER BY created_at DESC");
    $stmt->execute([$collegeId]);
    return $stmt->fetchAll();
}

// Get jobs for company
function getCompanyJobs($companyId) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
    $stmt->execute([$companyId]);
    return $stmt->fetchAll();
}

// Get notes for student
function getStudentNotes($studentId) {
    global $db;
    
    // Add subject and likes columns if they don't exist
    try {
        $db->exec("ALTER TABLE notes ADD COLUMN subject VARCHAR(100) DEFAULT 'General' AFTER description");
    } catch (PDOException $e) {
        // Column probably already exists, continue
    }
    
    try {
        $db->exec("ALTER TABLE notes ADD COLUMN likes INT DEFAULT 0 AFTER downloads");
    } catch (PDOException $e) {
        // Column probably already exists, continue
    }
    
    $stmt = $db->prepare("
        SELECT n.*, 
               CONCAT(COALESCE(s.name, ''), COALESCE(u.username, '')) as uploader_name,
               s.name as student_name,
               u.username
        FROM notes n
        LEFT JOIN students s ON n.student_id = s.id
        LEFT JOIN users u ON s.user_id = u.id
        WHERE n.shared_with = 'all' OR n.student_id = (
            SELECT id FROM students WHERE user_id = ?
        )
        ORDER BY n.created_at DESC
    ");
    $stmt->execute([$studentId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
