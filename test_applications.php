<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';

// Get database connection
$db = Database::getInstance()->getConnection();

// First, let's make sure we have a test user
$user_id = 1; // Assuming user ID 1 exists

// Insert some sample application data using correct column names
$insertSql = "INSERT INTO applications (user_id, type, title, company_or_org, status, applied_at) VALUES 
    (?, 'internship', 'Full Stack Developer Internship', 'Tech Corp', 'applied', DATE_SUB(NOW(), INTERVAL 2 DAY)),
    (?, 'job', 'React Developer Position', 'StartupX', 'in_process', DATE_SUB(NOW(), INTERVAL 5 DAY)),
    (?, 'internship', 'AI/ML Research Internship', 'AI Labs', 'in_process', DATE_SUB(NOW(), INTERVAL 7 DAY)),
    (?, 'job', 'Backend Developer Role', 'MegaCorp', 'rejected', DATE_SUB(NOW(), INTERVAL 10 DAY)),
    (?, 'internship', 'Web Developer Intern', 'WebDev Inc', 'selected', DATE_SUB(NOW(), INTERVAL 14 DAY)),
    (?, 'job', 'Mobile App Developer', 'AppMakers', 'applied', DATE_SUB(NOW(), INTERVAL 1 DAY)),
    (?, 'job', 'Data Scientist Position', 'DataTech', 'in_process', DATE_SUB(NOW(), INTERVAL 3 DAY)),
    (?, 'internship', 'DevOps Engineer Intern', 'CloudCorp', 'applied', DATE_SUB(NOW(), INTERVAL 6 DAY))";

try {
    $stmt = $db->prepare($insertSql);
    $stmt->execute([
        $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id
    ]);
    
    echo "Sample application data inserted successfully!\n";
    echo "Inserted " . $stmt->rowCount() . " applications.\n";
    
    // Show current applications
    $selectSql = "SELECT * FROM applications WHERE user_id = ? ORDER BY applied_at DESC";
    $stmt = $db->prepare($selectSql);
    $stmt->execute([$user_id]);
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nCurrent applications for user $user_id:\n";
    foreach ($applications as $app) {
        echo "- {$app['title']} at {$app['company_or_org']} - Status: {$app['status']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>