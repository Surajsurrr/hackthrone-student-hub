<?php
require_once 'includes/db_connect.php';

$db = Database::getInstance()->getConnection();
$stmt = $db->query('DESCRIBE applications');
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Applications table structure:\n";
foreach($columns as $col) {
    echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
}
?>