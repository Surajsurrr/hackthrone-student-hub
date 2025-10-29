<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

$userId = $_SESSION['user_id'];
global $db;

// Get stats
$opportunitiesCount = $db->fetchOne("SELECT COUNT(*) as count FROM (SELECT id FROM events WHERE status = 'active' UNION SELECT id FROM jobs WHERE status = 'active') as combined")['count'];

$notesCount = $db->fetchOne("SELECT COUNT(*) as count FROM notes WHERE shared_with = 'all' OR student_id = ?", [$userId])['count'];

$aiSessionsCount = $db->fetchOne("SELECT COUNT(*) as count FROM ai_responses WHERE student_id = ?", [$userId])['count'];

jsonResponse([
    'opportunities_count' => $opportunitiesCount,
    'notes_count' => $notesCount,
    'ai_sessions_count' => $aiSessionsCount
]);
?>
