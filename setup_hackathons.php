<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';

// Get database connection
$db = Database::getInstance()->getConnection();

// Insert sample college and hackathon data if not exists
try {
    // First, let's create a sample college user
    $checkUser = $db->prepare("SELECT id FROM users WHERE username = ? AND role = 'college'");
    $checkUser->execute(['mit_college']);
    $collegeUser = $checkUser->fetch();
    
    if (!$collegeUser) {
        // Create college user
        $insertUser = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'college')");
        $insertUser->execute(['mit_college', 'events@mit.edu', password_hash('password123', PASSWORD_DEFAULT)]);
        $collegeUserId = $db->lastInsertId();
        
        // Create college profile
        $insertCollege = $db->prepare("INSERT INTO colleges (user_id, name, location, description, contact_email) VALUES (?, ?, ?, ?, ?)");
        $insertCollege->execute([
            $collegeUserId,
            'MIT College of Engineering',
            'Boston, MA',
            'Leading engineering college organizing innovative hackathons and tech events',
            'events@mit.edu'
        ]);
        $collegeId = $db->lastInsertId();
    } else {
        // Get existing college
        $getCollege = $db->prepare("SELECT id FROM colleges WHERE user_id = ?");
        $getCollege->execute([$collegeUser['id']]);
        $college = $getCollege->fetch();
        $collegeId = $college['id'];
    }
    
    // Check if hackathons already exist
    $checkEvents = $db->prepare("SELECT COUNT(*) as count FROM events WHERE type = 'hackathon'");
    $checkEvents->execute();
    $eventCount = $checkEvents->fetch()['count'];
    
    if ($eventCount == 0) {
        // Insert sample hackathon events
        $hackathons = [
            [
                'title' => 'AI Innovation Hackathon 2025',
                'description' => 'Build the next generation AI applications in 48 hours. Prizes worth $10,000!',
                'date' => '2025-11-15 09:00:00',
                'location' => 'MIT Campus, Boston',
                'max_participants' => 200
            ],
            [
                'title' => 'Sustainable Tech Challenge',
                'description' => 'Create technology solutions for environmental challenges. Focus on sustainability and green tech.',
                'date' => '2025-11-22 10:00:00',
                'location' => 'MIT Innovation Lab',
                'max_participants' => 150
            ],
            [
                'title' => 'Mobile App Development Sprint',
                'description' => 'Design and develop innovative mobile applications. Android and iOS platforms welcome.',
                'date' => '2025-12-05 08:00:00',
                'location' => 'Computer Science Building',
                'max_participants' => 100
            ],
            [
                'title' => 'Blockchain & Web3 Hackathon',
                'description' => 'Explore the future of decentralized applications and blockchain technology.',
                'date' => '2025-12-12 09:30:00',
                'location' => 'MIT Blockchain Lab',
                'max_participants' => 120
            ]
        ];
        
        $insertEvent = $db->prepare("INSERT INTO events (college_id, title, description, date, location, type, max_participants, status) VALUES (?, ?, ?, ?, ?, 'hackathon', ?, 'active')");
        
        foreach ($hackathons as $hackathon) {
            $insertEvent->execute([
                $collegeId,
                $hackathon['title'],
                $hackathon['description'],
                $hackathon['date'],
                $hackathon['location'],
                $hackathon['max_participants']
            ]);
        }
        
        echo "Sample hackathon data inserted successfully!\n";
        echo "Added " . count($hackathons) . " hackathon events.\n";
    } else {
        echo "Hackathon events already exist. Count: $eventCount\n";
    }
    
    // Show current hackathons
    $selectSql = "SELECT e.*, c.name as college_name FROM events e JOIN colleges c ON e.college_id = c.id WHERE e.type = 'hackathon' ORDER BY e.date ASC";
    $stmt = $db->prepare($selectSql);
    $stmt->execute();
    $hackathons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nCurrent hackathon events:\n";
    foreach ($hackathons as $hackathon) {
        echo "- {$hackathon['title']} by {$hackathon['college_name']} on {$hackathon['date']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>