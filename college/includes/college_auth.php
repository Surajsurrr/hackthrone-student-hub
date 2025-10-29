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
    $db->update('colleges', $data, "user_id = $userId");
}
?>
