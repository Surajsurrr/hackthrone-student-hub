<?php
require_once '../includes/db_connect.php';

try {
    // Add document columns
    $pdo->exec("ALTER TABLE college_posts ADD COLUMN IF NOT EXISTS document_url VARCHAR(500) NULL AFTER image_url");
    $pdo->exec("ALTER TABLE college_posts ADD COLUMN IF NOT EXISTS document_name VARCHAR(255) NULL AFTER document_url");
    echo "✅ Document columns added successfully!<br>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "✅ Document columns already exist!<br>";
    } else {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
}

echo "<br><a href='../college/dashboard.php'>Go to College Dashboard</a>";
?>
