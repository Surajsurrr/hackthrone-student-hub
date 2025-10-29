<?php
require_once 'includes/student_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Coach - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h2>AI Career Coach</h2>
        <div class="ai-chat-container">
            <div id="chat-messages" class="chat-messages">
                <div class="message bot-message">
                    <p>Hello! I'm your AI career coach. How can I help you today?</p>
                </div>
            </div>
            <div class="chat-input-container">
                <input type="text" id="chat-input" placeholder="Ask me anything about your career..." class="chat-input">
                <button id="send-btn" class="btn">Send</button>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/ai_chat.js"></script>
</body>
</html>
