<?php
require_once 'includes/db_connect.php';

echo "<h2>Fix College Data</h2>";

try {
    // Check current college data
    $stmt = $pdo->query("SELECT * FROM colleges WHERE id = 1");
    $college = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Current College Data (ID: 1):</h3>";
    echo "<pre>";
    print_r($college);
    echo "</pre>";
    
    if (!$college) {
        echo "<p style='color: red;'>College ID 1 not found!</p>";
        exit;
    }
    
    // Check if name is empty
    if (empty($college['name'])) {
        echo "<p style='color: orange;'>⚠️ College name is empty. Fixing...</p>";
        
        // Update with a default name
        $stmt = $pdo->prepare("UPDATE colleges SET name = 'Default College Name' WHERE id = 1");
        $stmt->execute();
        
        echo "<p style='color: green;'>✅ Updated college name to 'Default College Name'</p>";
        echo "<p>Please go to your college profile and update it with the actual college name!</p>";
    } else {
        echo "<p style='color: green;'>✅ College name is set: {$college['name']}</p>";
    }
    
    // Also check if logo and location are set
    if (empty($college['logo'])) {
        echo "<p style='color: orange;'>⚠️ College logo is empty</p>";
    }
    if (empty($college['location'])) {
        echo "<p style='color: orange;'>⚠️ College location is empty</p>";
    }
    
    echo "<hr>";
    echo "<h3>Recommended Action:</h3>";
    echo "<ol>";
    echo "<li>Go to your College Dashboard</li>";
    echo "<li>Click on 'Profile' or 'Settings'</li>";
    echo "<li>Fill in: College Name, Logo URL, Location</li>";
    echo "<li>Save the profile</li>";
    echo "<li>Then create a new post to test</li>";
    echo "</ol>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>

<br><br>
<a href="test_posts.php" style="padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px;">Check Posts Again</a>
<a href="college/dashboard.php" style="padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">Go to College Dashboard</a>
