<?php
// AI Model Endpoint for Career Coaching
// This is a placeholder for the AI model integration
// In a real implementation, this would connect to an AI service like OpenAI, Hugging Face, etc.

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$query = $_GET['query'] ?? '';

if (empty($query)) {
    echo json_encode(['error' => 'Query parameter is required']);
    exit;
}

// Simple rule-based responses for demonstration
// In production, replace with actual AI model integration
$responses = [
    'career' => 'Based on your interests, consider roles in software development, data science, or product management. What specific field interests you most?',
    'interview' => 'For interview preparation, focus on: 1) Technical skills relevant to the role, 2) Behavioral questions, 3) Company research, 4) Practice coding problems. Would you like tips for a specific type of interview?',
    'resume' => 'A strong resume should highlight: achievements over duties, quantifiable results, relevant skills, and clear career progression. Keep it to 1 page and tailor it for each application.',
    'skills' => 'Key skills for tech careers include: programming languages (Python, JavaScript), frameworks (React, Django), databases (SQL, MongoDB), and soft skills like communication and problem-solving.',
    'networking' => 'Networking tips: attend industry events, join online communities, connect with alumni, participate in hackathons, and maintain a professional LinkedIn profile.',
    'default' => 'I\'m here to help with your career development! Ask me about interview preparation, resume building, skill development, networking, or career advice.'
];

$response = $responses['default'];

$query_lower = strtolower($query);
foreach ($responses as $key => $resp) {
    if ($key !== 'default' && strpos($query_lower, $key) !== false) {
        $response = $resp;
        break;
    }
}

echo json_encode([
    'query' => $query,
    'response' => $response,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
