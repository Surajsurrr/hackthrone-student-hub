<?php
require_once 'includes/functions.php';

// Quick fix for immediate testing - create a simple API response
header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
    exit;
}

// Return mock data to stop the unauthorized errors
jsonResponse([
    'success' => true,
    'opportunities_count' => 5,
    'notes_count' => 3,
    'ai_sessions_count' => 2,
    'events' => [],
    'jobs' => [],
    'notes' => []
]);
?>