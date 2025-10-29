<?php
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

global $db;

// Get events
$events = $db->fetchAll("SELECT e.*, c.name as college_name FROM events e JOIN colleges c ON e.college_id = c.id WHERE e.status = 'active' ORDER BY e.created_at DESC");

// Get jobs
$jobs = $db->fetchAll("SELECT j.*, comp.name as company_name FROM jobs j JOIN companies comp ON j.company_id = comp.id WHERE j.status = 'active' ORDER BY j.created_at DESC");

jsonResponse([
    'events' => $events,
    'jobs' => $jobs
]);
?>
