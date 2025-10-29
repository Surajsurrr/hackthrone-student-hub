<?php
echo "<h1>Test Page</h1>";
echo "<p>This is a simple test to verify PHP is working.</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";

// Test if we can access the stfinal directory
echo "<h2>Directory Test</h2>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Path: " . $_SERVER['SCRIPT_FILENAME'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

// Test basic links
echo "<h2>Test Links</h2>";
echo "<a href='index.php'>Index</a> | ";
echo "<a href='login.php'>Login</a> | ";
echo "<a href='student/dashboard.php'>Student Dashboard</a> | ";
echo "<a href='debug_session.php'>Debug Session</a>";

// Show all files in the directory
echo "<h2>Files in stfinal directory</h2>";
$files = scandir(__DIR__);
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "<li>$file</li>";
    }
}
echo "</ul>";
?>