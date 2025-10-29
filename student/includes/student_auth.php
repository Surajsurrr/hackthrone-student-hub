<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('student');

// Get student profile
function getStudentProfile($userId) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM students WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Update student profile
function updateStudentProfile($userId, $data) {
    global $db;
    $db->update('students', $data, "user_id = $userId");
}
?>
