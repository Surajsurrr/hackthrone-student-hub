<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
    exit;
}

try {
    $database = Database::getInstance();

    // Get events
    $events = $database->fetchAll("SELECT e.*, c.name as college_name FROM events e JOIN colleges c ON e.college_id = c.id WHERE e.status = 'active' ORDER BY e.created_at DESC LIMIT 10") ?? [];

    // Get jobs
    $jobs = $database->fetchAll("SELECT j.*, comp.name as company_name FROM jobs j JOIN companies comp ON j.company_id = comp.id WHERE j.status = 'active' ORDER BY j.created_at DESC LIMIT 10") ?? [];

    jsonResponse([
        'success' => true,
        'events' => $events,
        'jobs' => $jobs
    ]);
} catch (Exception $e) {
    error_log("getOpportunities API error: " . $e->getMessage());
    jsonResponse(['error' => 'Server error', 'success' => false], 500);
}
?>
