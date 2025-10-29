<?php
require_once 'includes/config.php';

echo "<h2>Setting up Backend Features Database Schema</h2>";

try {
    // Connect to database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color: green;'>✅ Connected to database</p>";
    
    // Read and execute the schema file
    $schema = file_get_contents('database/backend_features_schema.sql');
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && !str_starts_with(trim($statement), '--')) {
            try {
                $pdo->exec($statement);
                echo "<p style='color: green;'>✅ Executed: " . substr(trim($statement), 0, 50) . "...</p>";
            } catch (PDOException $e) {
                echo "<p style='color: orange;'>⚠️ Warning: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<h3>Verifying Created Tables:</h3>";
    
    $tables = [
        'applications',
        'shared_notes_analytics', 
        'ai_sessions',
        'ai_messages',
        'profile_scores',
        'student_achievements',
        'note_interactions',
        'application_status_history'
    ];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✅ Table '$table' created successfully</p>";
        } else {
            echo "<p style='color: red;'>❌ Table '$table' not found</p>";
        }
    }
    
    echo "<h3>Adding Sample Data for Testing:</h3>";
    
    // Add sample application data
    $pdo->exec("INSERT IGNORE INTO applications (student_id, opportunity_type, opportunity_id, opportunity_title, company_college_name, status) VALUES 
        (1, 'job', 1, 'Software Developer Internship', 'TechCorp Inc.', 'applied'),
        (1, 'event', 1, 'Coding Hackathon 2025', 'MIT University', 'under_review'),
        (1, 'job', 2, 'Data Analyst Position', 'DataCorp Ltd.', 'shortlisted')");
    
    // Add sample AI session data
    $pdo->exec("INSERT IGNORE INTO ai_sessions (student_id, session_title, conversation_topic, message_count, session_type) VALUES 
        (1, 'Career Guidance Session', 'Software Development Career Path', 15, 'career_guidance'),
        (1, 'Interview Preparation', 'Technical Interview Tips', 22, 'interview_prep'),
        (1, 'Skill Assessment', 'Programming Skills Evaluation', 8, 'skill_assessment')");
    
    // Add sample achievements
    $pdo->exec("INSERT IGNORE INTO student_achievements (student_id, achievement_type, achievement_title, achievement_description, points_earned, badge_icon) VALUES 
        (1, 'first_note', 'First Note Uploaded', 'Congratulations on sharing your first note!', 10, '📝'),
        (1, 'ai_explorer', 'AI Explorer', 'Had 5+ AI coaching sessions', 25, '🤖'),
        (1, 'active_learner', 'Active Learner', 'Logged in for 7 consecutive days', 15, '🎓')");
    
    // Initialize profile score
    $pdo->exec("INSERT IGNORE INTO profile_scores (student_id, total_score, achievements_score, notes_score, ai_sessions_score, applications_score) VALUES 
        (1, 75, 20, 15, 25, 15)");
    
    echo "<p style='color: green;'>✅ Sample data added successfully</p>";
    echo "<p><strong>Setup Complete!</strong> All backend features are now ready to use.</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<a href='student/dashboard.php'>Go to Dashboard</a> | ";
echo "<a href='test_backend_features.php'>Test Backend Features</a>";
?>