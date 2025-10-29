<?php
// Start session first so functions that rely on $_SESSION work correctly
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

$userId = $_SESSION['user_id'];

try {
    global $db;
    
    // Get applications for the current user
    $sql = "SELECT * FROM applications WHERE user_id = ? ORDER BY applied_at DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute([$userId]);
    $applications = $stmt->fetchAll();
    
    // Format the applications for display
    $formattedApplications = array_map(function($app) {
        return [
            'id' => $app['id'],
            'title' => $app['title'],
            'type' => $app['type'],
            'company' => $app['company_or_org'],
            'platform' => $app['platform'],
            'location' => $app['location'],
            'status' => $app['status'],
            'applied_at' => $app['applied_at'],
            'deadline' => $app['deadline'],
            'notes' => $app['notes']
        ];
    }, $applications);
    
    jsonResponse(['success' => true, 'applications' => $formattedApplications]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Failed to fetch applications: ' . $e->getMessage()], 500);
}
?>