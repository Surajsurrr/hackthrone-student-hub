<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
    exit;
}

try {
    $userId = $_SESSION['user_id'];
    $database = Database::getInstance();

    // Get stats
    $opportunitiesCount = $database->fetchOne("SELECT COUNT(*) as count FROM (SELECT id FROM events WHERE status = 'active' UNION SELECT id FROM jobs WHERE status = 'active') as combined")['count'] ?? 0;

    $notesCount = $database->fetchOne("SELECT COUNT(*) as count FROM notes WHERE shared_with = 'all' OR student_id = ?", [$userId])['count'] ?? 0;

    $aiSessionsCount = $database->fetchOne("SELECT COUNT(*) as count FROM ai_responses WHERE student_id = ?", [$userId])['count'] ?? 0;

    jsonResponse([
        'success' => true,
        'opportunities_count' => $opportunitiesCount,
        'notes_count' => $notesCount,
        'ai_sessions_count' => $aiSessionsCount
    ]);
} catch (Exception $e) {
    error_log("Dashboard API error: " . $e->getMessage());
    jsonResponse(['error' => 'Server error', 'success' => false], 500);
}
?>
