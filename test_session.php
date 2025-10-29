<?php
session_start();
echo "Session data:\n";
print_r($_SESSION);

if (isset($_SESSION['user_id'])) {
    echo "\nUser is logged in with ID: " . $_SESSION['user_id'];
} else {
    echo "\nUser is NOT logged in";
}
?>