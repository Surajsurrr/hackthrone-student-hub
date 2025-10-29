<?php
require_once '../../includes/session.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$student_id = $_SESSION['user_id'];

try {
    // Get all event applications for this student
    $stmt = $pdo->prepare("
        SELECT 
            ea.*,
            e.title as event_title,
            e.type as event_type,
            e.date as event_date,
            e.location as event_location,
            c.name as college_name,
            c.logo as college_logo
        FROM event_applications ea
        INNER JOIN events e ON ea.event_id = e.id
        INNER JOIN colleges c ON e.college_id = c.id
        WHERE ea.student_id = ?
        ORDER BY ea.applied_at DESC
    ");
    $stmt->execute([$student_id]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the applications
    $formattedApplications = [];
    foreach ($applications as $app) {
        $eventDate = new DateTime($app['event_date']);
        $appliedDate = new DateTime($app['applied_at']);
        
        $formattedApplications[] = [
            'id' => $app['id'],
            'event_title' => $app['event_title'],
            'event_type' => $app['event_type'],
            'event_date' => $app['event_date'],
            'event_date_formatted' => $eventDate->format('M d, Y'),
            'event_location' => $app['event_location'],
            'college_name' => $app['college_name'],
            'college_logo' => $app['college_logo'],
            'status' => $app['status'],
            'applied_at' => $app['applied_at'],
            'applied_at_formatted' => $appliedDate->format('M d, Y'),
            'full_name' => $app['full_name'],
            'email' => $app['email'],
            'phone' => $app['phone'],
            'college' => $app['college'],
            'year_of_study' => $app['year_of_study'],
            'branch' => $app['branch'],
            'team_name' => $app['team_name'],
            'team_size' => $app['team_size'],
            'motivation' => $app['motivation'],
            'experience' => $app['experience'],
            'skills' => $app['skills'],
            'github' => $app['github'],
            'linkedin' => $app['linkedin']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'applications' => $formattedApplications,
        'total_count' => count($formattedApplications),
        'pending_count' => count(array_filter($formattedApplications, fn($a) => $a['status'] === 'pending')),
        'approved_count' => count(array_filter($formattedApplications, fn($a) => $a['status'] === 'approved')),
        'rejected_count' => count(array_filter($formattedApplications, fn($a) => $a['status'] === 'rejected'))
    ]);
    
} catch (PDOException $e) {
    error_log("Get student applications error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch applications'
    ]);
}
?>
