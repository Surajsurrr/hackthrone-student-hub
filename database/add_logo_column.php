<?php
require_once '../includes/db_connect.php';

try {
    // Add logo column if it doesn't exist
    $pdo->exec("ALTER TABLE colleges ADD COLUMN IF NOT EXISTS logo VARCHAR(500) NULL AFTER website");
    echo "✅ Logo column added successfully!<br>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "✅ Logo column already exists!<br>";
    } else {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
}

echo "<br><a href='../college/dashboard.php'>Go to College Dashboard</a>";
?>
