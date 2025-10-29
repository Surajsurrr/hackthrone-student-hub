<?php
require_once 'includes/functions.php';

// Simulate a logged-in student for testing
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'student';
$_SESSION['username'] = 'test_user';
$_SESSION['email'] = 'test@example.com';

echo "<h1>API Authentication Test</h1>";

echo "<h2>Session Check:</h2>";
echo "Is logged in: " . (isLoggedIn() ? 'YES' : 'NO') . "<br>";
echo "Has student role: " . (hasRole('student') ? 'YES' : 'NO') . "<br>";
echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "<br>";

echo "<h2>Test API Endpoints:</h2>";

$apiEndpoints = [
    'getDashboard.php',
    'getOpportunities.php',
    'fetchNotes.php',
    'getProfile.php'
];

foreach ($apiEndpoints as $endpoint) {
    $url = "http://localhost/stfinal/api/student/$endpoint";
    
    echo "<h3>Testing: $endpoint</h3>";
    
    // Use cURL to test the endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode<br>";
    
    if ($httpCode == 200) {
        echo "<span style='color: green;'>✅ Working</span><br>";
    } elseif ($httpCode == 401) {
        echo "<span style='color: red;'>❌ Unauthorized</span><br>";
    } else {
        echo "<span style='color: orange;'>⚠️ Error ($httpCode)</span><br>";
    }
    
    echo "<br>";
}

echo "<hr>";
echo "<a href='student/dashboard.php'>Go to Student Dashboard</a>";
?>