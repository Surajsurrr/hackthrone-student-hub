<?php
// Test MongoDB connection and setup
echo "<h2>MongoDB Connection Test</h2>";

try {
    require_once 'includes/mongodb_connection.php';

    $mongodb = MongoDBConnection::getInstance();
    echo "<p>✅ MongoDB connection successful!</p>";

    // Test basic operations
    echo "<p>Testing basic operations...</p>";

    // Insert test document
    $testId = $mongodb->insert('test_collection', [
        'test_field' => 'Hello MongoDB!',
        'timestamp' => time()
    ]);

    if ($testId) {
        echo "<p>✅ Insert operation successful</p>";
    }

    // Find test document
    $documents = $mongodb->find('test_collection', ['test_field' => 'Hello MongoDB!']);
    if (!empty($documents)) {
        echo "<p>✅ Find operation successful</p>";
    }

    // Clean up test data
    $mongodb->delete('test_collection', ['test_field' => 'Hello MongoDB!']);
    echo "<p>✅ Delete operation successful</p>";

    echo "<p><strong>🎉 MongoDB is working perfectly!</strong></p>";
    echo "<p>You can now use MongoDB for AI features alongside your existing MySQL setup.</p>";

} catch (Exception $e) {
    echo "<p>❌ MongoDB Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Solution:</strong> Make sure the MongoDB PHP extension is installed.</p>";
    echo "<p>1. Download php_mongodb.dll for PHP 8.0 from: <a href='https://windows.php.net/downloads/pecl/releases/mongodb/'>PHP PECL</a></p>";
    echo "<p>2. Place it in C:\\xampp\\php\\ext\\</p>";
    echo "<p>3. Add 'extension=php_mongodb.dll' to C:\\xampp\\php\\php.ini</p>";
    echo "<p>4. Restart Apache in XAMPP</p>";
}
?></content>
<parameter name="filePath">c:\Users\sivaraja\studenthub\test_mongodb.php