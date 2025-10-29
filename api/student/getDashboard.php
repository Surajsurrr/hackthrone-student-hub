<?php
require_once 'api_auth.php';

requireStudentAuth();

try {
    $userId = $_SESSION['user_id'];
    $database = getApiDatabase();

    // Get stats with fallbacks for missing tables
    $opportunitiesCount = 0;
    $notesCount = 0;
    $aiSessionsCount = 0;
    
    try {
        $result = $database->fetchOne("SELECT COUNT(*) as count FROM events WHERE status = 'active'");
        $eventsCount = $result['count'] ?? 0;
        
        $result = $database->fetchOne("SELECT COUNT(*) as count FROM jobs WHERE status = 'active'");
        $jobsCount = $result['count'] ?? 0;
        
        $opportunitiesCount = $eventsCount + $jobsCount;
    } catch (Exception $e) {
        error_log("Events/Jobs query error: " . $e->getMessage());
    }
    
    try {
        $result = $database->fetchOne("SELECT COUNT(*) as count FROM notes WHERE shared_with = 'all' OR student_id = ?", [$userId]);
        $notesCount = $result['count'] ?? 0;
    } catch (Exception $e) {
        error_log("Notes query error: " . $e->getMessage());
    }
    
    try {
        $result = $database->fetchOne("SELECT COUNT(*) as count FROM ai_responses WHERE student_id = ?", [$userId]);
        $aiSessionsCount = $result['count'] ?? 0;
    } catch (Exception $e) {
        error_log("AI responses query error: " . $e->getMessage());
    }

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
