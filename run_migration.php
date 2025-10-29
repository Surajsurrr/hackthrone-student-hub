<?php
$conn = new mysqli('localhost', 'root', '', 'studenthub');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$sql = file_get_contents('database/migrations/2025_10_29_enhanced_notes_organization.sql');
if ($conn->multi_query($sql)) {
    echo 'Enhanced notes organization migration executed successfully!' . PHP_EOL;
    while ($conn->next_result()) {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    }
} else {
    echo 'Error: ' . $conn->error . PHP_EOL;
}
$conn->close();
?>