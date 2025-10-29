<?php
// Start session to simulate login
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'testuser';

// Include the API file
require_once 'api/student/getApplications.php';
?>