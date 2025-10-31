<?php
// Load configuration with error checking
$config_file = __DIR__ . '/config.php';
if (!file_exists($config_file)) {
    die('Config file not found at: ' . $config_file);
}
require_once $config_file;

// Check if constants are defined
if (!defined('SESSION_LIFETIME')) {
    define('SESSION_LIFETIME', 3600); // Fallback value
}

// Start session with custom settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

// Set session save path to a writable directory
$session_save_path = sys_get_temp_dir();
if (is_writable($session_save_path)) {
    session_save_path($session_save_path);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug session information
if (defined('DEBUG') && DEBUG) {
    error_log("Session started. Session ID: " . session_id());
    error_log("Session data: " . print_r($_SESSION, true));
}

// Regenerate session ID periodically for security
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutes
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Destroy session
function destroySession() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

// Logout function
function logout() {
    destroySession();
    // Redirect to main landing page after logout
    // Use absolute path to ensure all portals (student/college/company) redirect correctly
    header('Location: /stfinal/index.php');
    exit;
}
?>
