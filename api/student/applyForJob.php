<?php
require_once '../../includes/session.php';
require_once '../../includes/functions.php';
require_once '../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !hasRole('student')) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$userId = $_SESSION['user_id'];
$jobId = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;

if (!$jobId) {
    jsonResponse(['error' => 'Job ID is required'], 400);
}

try {
    $pdo = Database::getInstance()->getConnection();
    
    // Drop and recreate the table to ensure correct schema
    $pdo->exec("DROP TABLE IF EXISTS job_applications");
    
    // Create job_applications table with correct schema
    $createTableSQL = "CREATE TABLE job_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        job_id INT NOT NULL,
        resume_path VARCHAR(255) NOT NULL,
        cover_letter TEXT,
        status VARCHAR(50) DEFAULT 'pending',
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_job_id (job_id),
        INDEX idx_status (status),
        UNIQUE KEY unique_application (user_id, job_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $pdo->exec($createTableSQL);
    
    // Check if student has already applied for this job
    $checkStmt = $pdo->prepare("SELECT id FROM job_applications WHERE user_id = ? AND job_id = ?");
    $checkStmt->execute([$userId, $jobId]);
    if ($checkStmt->fetch()) {
        jsonResponse(['error' => 'You have already applied for this job'], 400);
    }

    // Handle resume upload
    $resumePath = null;
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../student/uploads/resumes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'doc', 'docx'];
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            jsonResponse(['error' => 'Invalid file type. Only PDF, DOC, and DOCX are allowed'], 400);
        }

        $fileName = 'resume_' . $userId . '_' . time() . '.' . $fileExtension;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetPath)) {
            $resumePath = 'uploads/resumes/' . $fileName;
        } else {
            jsonResponse(['error' => 'Failed to upload resume'], 500);
        }
    } else {
        jsonResponse(['error' => 'Resume is required'], 400);
    }

    // Insert application
    $stmt = $pdo->prepare("
        INSERT INTO job_applications (user_id, job_id, resume_path, cover_letter, status) 
        VALUES (?, ?, ?, ?, 'pending')
    ");
    
    $coverLetter = isset($_POST['cover_letter']) ? trim($_POST['cover_letter']) : '';
    
    $stmt->execute([
        $userId,
        $jobId,
        $resumePath,
        $coverLetter
    ]);

    jsonResponse([
        'success' => true,
        'message' => 'Application submitted successfully!',
        'application_id' => $pdo->lastInsertId()
    ], 201);

} catch (Exception $e) {
    error_log("Error submitting application: " . $e->getMessage());
    jsonResponse(['error' => 'Failed to submit application: ' . $e->getMessage()], 500);
}
?>
