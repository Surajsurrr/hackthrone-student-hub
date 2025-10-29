<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

$user = getCurrentUser();
$profile = getStudentProfile($user['id']);

jsonResponse([
    'user' => $user,
    'profile' => $profile
]);
?>
