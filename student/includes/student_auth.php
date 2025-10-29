<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Debug information
if (defined('DEBUG') && DEBUG) {
    error_log("Session data in student_auth: " . print_r($_SESSION, true));
}

requireRole('student');

// Get student profile
function getStudentProfile($userId) {
    try {
        $database = Database::getInstance();
        $student = $database->fetchOne("SELECT * FROM students WHERE user_id = ?", [$userId]);
        return $student ?: [];
    } catch (Exception $e) {
        error_log("Error getting student profile: " . $e->getMessage());
        return [];
    }
}

// Update student profile
function updateStudentProfile($userId, $data) {
    if (empty($data) || !is_array($data)) return false;

    try {
        $database = Database::getInstance();
        $setParts = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $setParts[] = "$key = :$key";
            $params[$key] = $value;
        }
        
        $params['user_id'] = $userId;
        $setSql = implode(', ', $setParts);
        $sql = "UPDATE students SET $setSql WHERE user_id = :user_id";
        
        $database->query($sql, $params);
        return true;
    } catch (Exception $e) {
        error_log("Error updating student profile: " . $e->getMessage());
        return false;
    }
}
?>
