<?php
require_once __DIR__ . '/mongodb_connection.php';

class AITrainingData {
    private $mongodb;

    public function __construct() {
        $this->mongodb = MongoDBConnection::getInstance();
    }

    // Store training data
    public function storeTrainingData($userId, $data, $tags = []) {
        $document = [
            'user_id' => $userId,
            'type' => 'training_data',
            'data' => $data,
            'tags' => $tags,
            'status' => 'active'
        ];

        return $this->mongodb->insert('training_data', $document);
    }

    // Get training data for user
    public function getTrainingData($userId, $limit = 50) {
        return $this->mongodb->find('training_data', [
            'user_id' => $userId,
            'status' => 'active'
        ], [
            'limit' => $limit,
            'sort' => ['created_at' => -1]
        ]);
    }

    // Store JSON training file
    public function storeJSONFile($userId, $filename, $content, $metadata = []) {
        $document = [
            'user_id' => $userId,
            'type' => 'json_file',
            'filename' => $filename,
            'content' => $content,
            'metadata' => $metadata,
            'file_size' => strlen($content),
            'status' => 'active'
        ];

        return $this->mongodb->insert('training_files', $document);
    }

    // Get JSON files for user
    public function getJSONFiles($userId) {
        return $this->mongodb->find('training_files', [
            'user_id' => $userId,
            'type' => 'json_file',
            'status' => 'active'
        ], [
            'sort' => ['created_at' => -1]
        ]);
    }
}

class AIConversations {
    private $mongodb;

    public function __construct() {
        $this->mongodb = MongoDBConnection::getInstance();
    }

    // Start new conversation
    public function startConversation($userId, $title = 'New Conversation') {
        $document = [
            'user_id' => $userId,
            'title' => $title,
            'messages' => [],
            'status' => 'active',
            'model' => MISTRAL_MODEL
        ];

        $conversationId = $this->mongodb->insert('conversations', $document);
        return $conversationId;
    }

    // Add message to conversation
    public function addMessage($conversationId, $role, $content, $metadata = []) {
        $message = [
            'role' => $role, // 'user' or 'assistant'
            'content' => $content,
            'timestamp' => new MongoDB\BSON\UTCDateTime(),
            'metadata' => $metadata
        ];

        // Update conversation with new message
        $result = $this->mongodb->update(
            'conversations',
            ['_id' => new MongoDB\BSON\ObjectId($conversationId)],
            ['$push' => ['messages' => $message]]
        );

        return $result;
    }

    // Get conversation
    public function getConversation($conversationId) {
        return $this->mongodb->findOne('conversations', [
            '_id' => new MongoDB\BSON\ObjectId($conversationId)
        ]);
    }

    // Get user's conversations
    public function getUserConversations($userId, $limit = 20) {
        return $this->mongodb->find('conversations', [
            'user_id' => $userId,
            'status' => 'active'
        ], [
            'limit' => $limit,
            'sort' => ['updated_at' => -1]
        ]);
    }

    // Update conversation title
    public function updateConversationTitle($conversationId, $title) {
        return $this->mongodb->update(
            'conversations',
            ['_id' => new MongoDB\BSON\ObjectId($conversationId)],
            ['$set' => ['title' => $title]]
        );
    }
}

class MistralAI {
    private $apiKey;
    private $baseUrl;
    private $model;

    public function __construct() {
        $this->apiKey = MISTRAL_API_KEY;
        $this->baseUrl = MISTRAL_BASE_URL;
        $this->model = MISTRAL_MODEL;
    }

    // Send chat completion request
    public function chatCompletion($messages, $options = []) {
        $payload = [
            'model' => $options['model'] ?? $this->model,
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? 0.7,
            'max_tokens' => $options['max_tokens'] ?? 1000,
            'stream' => false
        ];

        return $this->makeRequest('chat/completions', $payload);
    }

    // Make HTTP request to Mistral AI
    private function makeRequest($endpoint, $payload) {
        $url = $this->baseUrl . '/' . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Mistral AI API Error: HTTP $httpCode - $response");
        }

        return json_decode($response, true);
    }

    // Generate response for conversation
    public function generateResponse($conversationId, $userMessage, $conversations) {
        // Get conversation history
        $conversation = $conversations->getConversation($conversationId);
        $messages = $conversation['messages'] ?? [];

        // Add user message
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        // Get AI response
        $response = $this->chatCompletion($messages);
        $aiMessage = $response['choices'][0]['message']['content'];

        // Store messages in conversation
        $conversations->addMessage($conversationId, 'user', $userMessage);
        $conversations->addMessage($conversationId, 'assistant', $aiMessage);

        return $aiMessage;
    }
}
?></content>
<parameter name="filePath">c:\Users\sivaraja\studenthub\includes\ai_models.php