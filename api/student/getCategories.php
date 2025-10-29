<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');

require_once '../../includes/config.php';
require_once '../../includes/db_connect.php';

try {
    // Fetch all subjects with their topic counts
    $subjectsQuery = "
        SELECT 
            subject_category as name,
            COUNT(t.id) as topic_count,
            COUNT(n.id) as notes_count
        FROM (
            SELECT DISTINCT subject_category FROM topics
            UNION 
            SELECT DISTINCT subject FROM notes WHERE subject IS NOT NULL
        ) as subjects
        LEFT JOIN topics t ON subjects.subject_category = t.subject_category
        LEFT JOIN notes n ON subjects.subject_category = n.subject
        GROUP BY subject_category
        ORDER BY notes_count DESC
    ";
    
    $subjectsResult = $conn->query($subjectsQuery);
    $subjects = [];
    
    while ($row = $subjectsResult->fetch_assoc()) {
        $subjects[] = [
            'name' => $row['name'],
            'topic_count' => (int)$row['topic_count'],
            'notes_count' => (int)$row['notes_count'],
            'icon' => getSubjectIcon($row['name'])
        ];
    }
    
    // Fetch all topics grouped by subject
    $topicsQuery = "
        SELECT 
            t.*,
            COUNT(nt.note_id) as usage_count
        FROM topics t
        LEFT JOIN note_topics nt ON t.id = nt.topic_id
        GROUP BY t.id
        ORDER BY t.subject_category, usage_count DESC
    ";
    
    $topicsResult = $conn->query($topicsQuery);
    $topics = [];
    
    while ($row = $topicsResult->fetch_assoc()) {
        $topics[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'subject_category' => $row['subject_category'],
            'description' => $row['description'],
            'color' => $row['color'],
            'icon' => $row['icon'],
            'usage_count' => (int)$row['usage_count']
        ];
    }
    
    // Fetch popular tags
    $tagsQuery = "
        SELECT 
            t.*,
            COUNT(nt.note_id) as usage_count
        FROM tags t
        LEFT JOIN note_tags nt ON t.id = nt.tag_id
        GROUP BY t.id
        ORDER BY usage_count DESC
        LIMIT 20
    ";
    
    $tagsResult = $conn->query($tagsQuery);
    $tags = [];
    
    while ($row = $tagsResult->fetch_assoc()) {
        $tags[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'usage_count' => (int)$row['usage_count']
        ];
    }
    
    // Get overall statistics
    $statsQuery = "
        SELECT 
            (SELECT COUNT(*) FROM notes) as total_notes,
            (SELECT COUNT(DISTINCT subject_category) FROM topics) as total_subjects,
            (SELECT COUNT(*) FROM topics) as total_topics,
            (SELECT COUNT(DISTINCT student_id) FROM notes) as contributors
    ";
    
    $statsResult = $conn->query($statsQuery);
    $stats = $statsResult->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'subjects' => $subjects,
        'topics' => $topics,
        'tags' => $tags,
        'stats' => [
            'total_notes' => (int)$stats['total_notes'],
            'total_subjects' => (int)$stats['total_subjects'],
            'total_topics' => (int)$stats['total_topics'],
            'contributors' => (int)$stats['contributors']
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to fetch categories: ' . $e->getMessage()
    ]);
}

function getSubjectIcon($subject) {
    $icons = [
        'Computer Science' => '💻',
        'Mathematics' => '📐',
        'Physics' => '⚛️',
        'Chemistry' => '🧪',
        'Biology' => '🧬',
        'English' => '📚',
        'History' => '🏛️',
        'Other' => '📋'
    ];
    
    return $icons[$subject] ?? '📚';
}
?>