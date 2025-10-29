<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');

require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $endorsee_name = trim($input['endorsee_name'] ?? '');
    $skill = trim($input['skill'] ?? '');
    $message = trim($input['message'] ?? '');
    $endorser_id = $_SESSION['user_id'];

    // Validate required fields
    if (empty($endorsee_name) || empty($skill) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    // Find endorsee by name (you might want to use ID instead)
    $stmt = $db->prepare("SELECT id FROM students WHERE CONCAT(first_name, ' ', last_name) LIKE ?");
    $stmt->execute(["%$endorsee_name%"]);
    $endorsee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$endorsee) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    // Check if endorsements table exists, if not create it
    $checkTable = $db->query("SHOW TABLES LIKE 'endorsements'");
    if ($checkTable->rowCount() == 0) {
        $createTable = "
        CREATE TABLE endorsements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            endorser_id INT NOT NULL,
            endorsee_id INT NOT NULL,
            skill VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (endorser_id) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (endorsee_id) REFERENCES students(id) ON DELETE CASCADE
        )";
        $db->exec($createTable);
    }

    // Insert endorsement
    $stmt = $db->prepare("
        INSERT INTO endorsements (endorser_id, endorsee_id, skill, message) 
        VALUES (?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([$endorser_id, $endorsee['id'], $skill, $message]);

    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Endorsement submitted successfully!'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit endorsement']);
    }

} catch (Exception $e) {
    error_log("Endorsement submission error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
}
?>