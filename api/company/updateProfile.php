<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('company')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];

$name = isset($input['name']) ? trim($input['name']) : '';
$industry = isset($input['industry']) ? trim($input['industry']) : '';
$description = isset($input['description']) ? trim($input['description']) : '';

if (empty($name)) {
    jsonResponse(['error' => 'Company name is required'], 400);
}

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Check if company profile exists
    $stmt = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
    $stmt->execute([$userId]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($company) {
        // Update existing profile
        $stmt = $pdo->prepare("
            UPDATE companies 
            SET name = ?, industry = ?, description = ?, updated_at = NOW()
            WHERE user_id = ?
        ");
        $stmt->execute([$name, $industry, $description, $userId]);
        $message = 'Profile updated successfully!';
    } else {
        // Create new profile
        $stmt = $pdo->prepare("
            INSERT INTO companies (user_id, name, industry, description, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$userId, $name, $industry, $description]);
        $message = 'Profile created successfully!';
    }

    jsonResponse([
        'success' => true,
        'message' => $message
    ]);
    
} catch (Exception $e) {
    error_log("Error updating company profile: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to update profile'], 500);
}
?>
