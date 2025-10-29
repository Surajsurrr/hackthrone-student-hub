<?php
// Test config file
echo "Testing config.php file...<br>";

$config_path = __DIR__ . '/includes/config.php';
echo "Config path: " . $config_path . "<br>";

if (file_exists($config_path)) {
    echo "Config file exists<br>";
    require_once $config_path;
    
    if (defined('SESSION_LIFETIME')) {
        echo "SESSION_LIFETIME is defined: " . SESSION_LIFETIME . "<br>";
    } else {
        echo "SESSION_LIFETIME is NOT defined<br>";
    }
    
    if (defined('APP_NAME')) {
        echo "APP_NAME is defined: " . APP_NAME . "<br>";
    } else {
        echo "APP_NAME is NOT defined<br>";
    }
} else {
    echo "Config file does NOT exist<br>";
}
?>