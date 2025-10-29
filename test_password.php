<?php
// Test password functions
require_once 'includes/functions.php';

echo "<h2>Password Function Test</h2>";

// Test password hashing
$password = 'test123';
$hash = hashPassword($password);
echo "<p>Original password: $password</p>";
echo "<p>Hashed password: $hash</p>";

// Test password verification
$verify = verifyPassword($password, $hash);
echo "<p>Verification result: " . ($verify ? '✅ Success' : '❌ Failed') . "</p>";

// Test wrong password
$wrongVerify = verifyPassword('wrongpassword', $hash);
echo "<p>Wrong password verification: " . ($wrongVerify ? '❌ Should fail' : '✅ Correctly failed') . "</p>";
?>