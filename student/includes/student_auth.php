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
    if (empty($data) || !is_array($data)) return;

    $setParts = [];
    $params = [];
    foreach ($data as $key => $value) {
        $setParts[] = "$key = :$key";
        $params[$key] = $value;
    }
    $params['user_id'] = $userId;
    $setSql = implode(', ', $setParts);
    $sql = "UPDATE students SET $setSql WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
}
?>
