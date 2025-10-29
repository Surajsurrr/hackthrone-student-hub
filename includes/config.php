<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'studenthub');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('APP_NAME', 'LearnX');
define('APP_URL', 'http://localhost/studenthub');

// File upload configuration
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

// Session configuration
define('SESSION_LIFETIME', 3600); // 1 hour

// AI configuration
define('AI_MODEL_ENDPOINT', 'ai/model_endpoint.php');
?>
