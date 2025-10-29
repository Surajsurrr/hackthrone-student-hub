<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

requireRole('company');

// Get company profile
function getCompanyProfile($userId) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM companies WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

// Update company profile
function updateCompanyProfile($userId, $data) {
    global $db;
    $db->update('companies', $data, "user_id = $userId");
}
?>
