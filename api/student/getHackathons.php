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

try {
    // Get hackathon events posted by colleges - remove date filter to show all events
    $sql = "SELECT 
        e.id,
        e.title,
        e.description,
        e.date,
        e.location,
        e.max_participants,
        e.status,
        e.created_at,
        c.name as college_name,
        c.logo as college_logo,
        c.location as college_location
    FROM events e
    INNER JOIN colleges c ON e.college_id = c.id
    WHERE e.type = 'hackathon' 
    AND e.status = 'active'
    ORDER BY e.date ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $hackathons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the data for frontend
    $formattedHackathons = [];
    foreach ($hackathons as $hackathon) {
        $eventDate = new DateTime($hackathon['date']);
        $now = new DateTime();
        $daysUntil = $now->diff($eventDate)->days;
        
        $formattedHackathons[] = [
            'id' => $hackathon['id'],
            'title' => $hackathon['title'],
            'description' => $hackathon['description'],
            'date' => $hackathon['date'],
            'formatted_date' => $eventDate->format('M d, Y'),
            'formatted_time' => $eventDate->format('h:i A'),
            'day' => $eventDate->format('d'),
            'month' => $eventDate->format('M'),
            'location' => $hackathon['location'],
            'max_participants' => $hackathon['max_participants'],
            'college_name' => $hackathon['college_name'],
            'college_location' => $hackathon['college_location'],
            'days_until' => $daysUntil,
            'is_today' => $eventDate->format('Y-m-d') === $now->format('Y-m-d'),
            'is_tomorrow' => $eventDate->format('Y-m-d') === $now->modify('+1 day')->format('Y-m-d')
        ];
    }
    
    echo json_encode([
        'success' => true,
        'hackathons' => $formattedHackathons,
        'total_count' => count($formattedHackathons)
    ]);
    
} catch (Exception $e) {
    error_log("Error in getHackathons.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch hackathon events: ' . $e->getMessage()
    ]);
}
    ]);
}
?>