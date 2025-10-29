<?php
require_once 'api_auth.php';

requireStudentAuth();

try {
    $userId = $_SESSION['user_id'];
    $database = getApiDatabase();
    
    // Get all applications for the student using correct column names
    $applications = $database->fetchAll("
        SELECT 
            a.*,
            a.title as opportunity_title,
            a.company_or_org as company_college_name,
            a.applied_at as application_date,
            CASE 
                WHEN a.type = 'job' THEN 'Job'
                WHEN a.type = 'internship' THEN 'Internship'
                WHEN a.type = 'scholarship' THEN 'Scholarship'
                WHEN a.type = 'competition' THEN 'Competition'
                WHEN a.type = 'exam_alert' THEN 'Exam'
                ELSE 'Other'
            END as type_display,
            DATEDIFF(CURDATE(), a.applied_at) as days_since_applied,
            CASE 
                WHEN a.status = 'applied' THEN '#3B82F6'
                WHEN a.status = 'in_process' THEN '#F59E0B'
                WHEN a.status = 'selected' THEN '#10B981'
                WHEN a.status = 'rejected' THEN '#EF4444'
                ELSE '#6B7280'
            END as status_color
        FROM applications a 
        WHERE a.user_id = ? 
        ORDER BY a.applied_at DESC
    ", [$userId]);
    
    // Get application statistics
    $stats = $database->fetchOne("
        SELECT 
            COUNT(*) as total_applications,
            SUM(CASE WHEN status = 'applied' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'in_process' THEN 1 ELSE 0 END) as under_review,
            SUM(CASE WHEN status = 'in_process' THEN 1 ELSE 0 END) as shortlisted,
            SUM(CASE WHEN status = 'selected' THEN 1 ELSE 0 END) as accepted,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM applications 
        WHERE user_id = ?
    ", [$userId]);
    
    jsonResponse([
        'success' => true,
        'applications' => $applications ?? [],
        'stats' => $stats ?? []
    ]);
    
} catch (Exception $e) {
    error_log("Applications API error: " . $e->getMessage());
    jsonResponse(['error' => 'Server error', 'success' => false], 500);
}
?>