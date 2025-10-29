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
    // Get event type filter from request
    $eventType = isset($_GET['type']) ? $_GET['type'] : 'all';
    
    // Build SQL query based on filter - remove date filter to show all events
    $whereClause = "WHERE e.status = 'active'";
    $params = [];
    
    if ($eventType !== 'all') {
        $whereClause .= " AND e.type = ?";
        $params[] = $eventType;
    }
    
    $sql = "SELECT 
        e.id,
        e.title,
        e.description,
        e.date,
        e.location,
        e.type,
        e.max_participants,
        e.status,
        e.created_at,
        c.name as college_name,
        c.logo as college_logo,
        c.location as college_location
    FROM events e
    INNER JOIN colleges c ON e.college_id = c.id
    $whereClause
    ORDER BY e.date ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the data for frontend
    $formattedEvents = [];
    foreach ($events as $event) {
        $eventDate = new DateTime($event['date']);
        $now = new DateTime();
        $daysUntil = $now->diff($eventDate)->days;
        
        // Map event type to display format
        $typeDisplayMap = [
            'hackathon' => 'Hackathon',
            'workshop' => 'Workshop',
            'symposium' => 'Symposium',
            'project-expo' => 'Project Expo',
            'other' => 'Event'
        ];
        
        $formattedEvents[] = [
            'id' => $event['id'],
            'title' => $event['title'],
            'description' => $event['description'],
            'date' => $event['date'],
            'formatted_date' => $eventDate->format('M d, Y'),
            'formatted_time' => $eventDate->format('h:i A'),
            'day' => $eventDate->format('d'),
            'month' => $eventDate->format('M'),
            'location' => $event['location'],
            'type' => $event['type'],
            'type_display' => $typeDisplayMap[$event['type']] ?? 'Event',
            'max_participants' => $event['max_participants'],
            'college_name' => $event['college_name'],
            'college_location' => $event['college_location'],
            'days_until' => $daysUntil,
            'is_today' => $eventDate->format('Y-m-d') === $now->format('Y-m-d'),
            'is_tomorrow' => $eventDate->format('Y-m-d') === $now->modify('+1 day')->format('Y-m-d')
        ];
    }
    
    // Get event counts by type - remove date filter to show all events
    $countSql = "SELECT 
        e.type,
        COUNT(*) as count
    FROM events e
    INNER JOIN colleges c ON e.college_id = c.id
    WHERE e.status = 'active'
    GROUP BY e.type";
    
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute();
    $counts = $countStmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    echo json_encode([
        'success' => true,
        'events' => $formattedEvents,
        'total_count' => count($formattedEvents),
        'counts_by_type' => $counts,
        'filter_applied' => $eventType
    ]);
    
} catch (Exception $e) {
    error_log("Error in getEvents.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch events: ' . $e->getMessage()
    ]);
}
?>