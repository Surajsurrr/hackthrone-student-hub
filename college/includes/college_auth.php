<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('college');

// Get college profile
function getCollegeProfile($userId) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM colleges WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Update college profile
function updateCollegeProfile($userId, $data) {
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
    $sql = "UPDATE colleges SET $setSql WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
}
?>
