<?php
// Common authentication for API endpoints
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');

// Check authentication
function requireStudentAuth() {
    if (!isLoggedIn()) {
        if (defined('DEBUG') && DEBUG) {
            error_log("API: User not logged in. Session: " . print_r($_SESSION, true));
        }
        jsonResponse(['error' => 'Not logged in', 'success' => false], 401);
        exit;
    }
    
    if (!hasRole('student')) {
        if (defined('DEBUG') && DEBUG) {
            error_log("API: User does not have student role. Current user: " . print_r(getCurrentUser(), true));
        }
        jsonResponse(['error' => 'Insufficient permissions', 'success' => false], 403);
        exit;
    }
    
    return true;
}

// Get database instance for API use
function getApiDatabase() {
    try {
        return Database::getInstance();
    } catch (Exception $e) {
        error_log("API Database error: " . $e->getMessage());
        jsonResponse(['error' => 'Database connection failed', 'success' => false], 500);
        exit;
    }
}
?>