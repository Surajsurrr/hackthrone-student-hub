<?php
require_once __DIR__ . '/mongodb_config.php';

class MongoDBConnection {
    private static $instance = null;
    private $client;
    private $database;

    private function __construct() {
        try {
            // MongoDB connection string
            $connectionString = 'mongodb://' . MONGODB_HOST . ':' . MONGODB_PORT;

            // Add authentication if configured
            if (!empty(MONGODB_USERNAME) && !empty(MONGODB_PASSWORD)) {
                $connectionString = 'mongodb://' . MONGODB_USERNAME . ':' . MONGODB_PASSWORD . '@' . MONGODB_HOST . ':' . MONGODB_PORT;
            }

            $this->client = new MongoDB\Driver\Manager($connectionString);
            $this->database = MONGODB_DATABASE;

            // Test connection
            $this->ping();

        } catch (Exception $e) {
            error_log("MongoDB Connection Error: " . $e->getMessage());
            throw new Exception("Failed to connect to MongoDB: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new MongoDBConnection();
        }
        return self::$instance;
    }

    public function getClient() {
        return $this->client;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function ping() {
        $command = new MongoDB\Driver\Command(['ping' => 1]);
        $this->client->executeCommand('admin', $command);
        return true;
    }

    // Insert document
    public function insert($collection, $document) {
        try {
            $bulk = new MongoDB\Driver\BulkWrite();
            $document['_id'] = new MongoDB\BSON\ObjectId();
            $document['created_at'] = new MongoDB\BSON\UTCDateTime();
            $document['updated_at'] = new MongoDB\BSON\UTCDateTime();

            $bulk->insert($document);
            $result = $this->client->executeBulkWrite($this->database . '.' . $collection, $bulk);

            return $document['_id'];
        } catch (Exception $e) {
            error_log("MongoDB Insert Error: " . $e->getMessage());
            return false;
        }
    }

    // Find documents
    public function find($collection, $filter = [], $options = []) {
        try {
            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->client->executeQuery($this->database . '.' . $collection, $query);

            $results = [];
            foreach ($cursor as $document) {
                $results[] = $this->convertBSONToArray($document);
            }

            return $results;
        } catch (Exception $e) {
            error_log("MongoDB Find Error: " . $e->getMessage());
            return [];
        }
    }

    // Find one document
    public function findOne($collection, $filter = []) {
        $results = $this->find($collection, $filter, ['limit' => 1]);
        return !empty($results) ? $results[0] : null;
    }

    // Update document
    public function update($collection, $filter, $update) {
        try {
            $bulk = new MongoDB\Driver\BulkWrite();
            $update['$set']['updated_at'] = new MongoDB\BSON\UTCDateTime();

            $bulk->update($filter, $update);
            $result = $this->client->executeBulkWrite($this->database . '.' . $collection, $bulk);

            return $result->getModifiedCount();
        } catch (Exception $e) {
            error_log("MongoDB Update Error: " . $e->getMessage());
            return false;
        }
    }

    // Delete document
    public function delete($collection, $filter) {
        try {
            $bulk = new MongoDB\Driver\BulkWrite();
            $bulk->delete($filter);

            $result = $this->client->executeBulkWrite($this->database . '.' . $collection, $bulk);
            return $result->getDeletedCount();
        } catch (Exception $e) {
            error_log("MongoDB Delete Error: " . $e->getMessage());
            return false;
        }
    }

    // Convert BSON document to PHP array
    private function convertBSONToArray($document) {
        $array = json_decode(json_encode($document), true);

        // Convert ObjectId to string
        if (isset($array['_id']) && is_array($array['_id'])) {
            $array['_id'] = $array['_id']['$oid'];
        }

        // Convert UTCDateTime to readable format
        foreach (['created_at', 'updated_at'] as $field) {
            if (isset($array[$field]) && is_array($array[$field])) {
                $array[$field] = date('Y-m-d H:i:s', $array[$field]['$date']['$numberLong'] / 1000);
            }
        }

        return $array;
    }
}
?></content>
<parameter name="filePath">c:\Users\sivaraja\studenthub\includes\mongodb_connection.php