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
    if (empty($data) || !is_array($data)) return;

    $setParts = [];
    $params = [];
    foreach ($data as $key => $value) {
        $setParts[] = "$key = :$key";
        $params[$key] = $value;
    }
    $params['user_id'] = $userId;
    $setSql = implode(', ', $setParts);
    $sql = "UPDATE companies SET $setSql WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
}
?>
