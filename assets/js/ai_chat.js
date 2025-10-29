// AI Chat functionality

document.addEventListener('DOMContentLoaded', function() {
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');
    const chatMessages = document.getElementById('chat-messages');

    if (!chatInput || !sendBtn || !chatMessages) return;

    // Send message on button click
    sendBtn.addEventListener('click', sendMessage);

    // Send message on Enter key
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');
        chatInput.value = '';

        // Show typing indicator
        const typingIndicator = addTypingIndicator();

        // Send to AI
        fetch('api/student/getAIResponse.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ query: message })
        })
        .then(response => response.json())
        .then(data => {
            // Remove typing indicator
            typingIndicator.remove();

            if (data.response) {
                addMessage(data.response, 'bot');
            } else {
                addMessage('Sorry, I couldn\'t process your request. Please try again.', 'bot');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            typingIndicator.remove();
            addMessage('Sorry, there was an error connecting to the AI service.', 'bot');
        });
    }

    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;

        const messageContent = document.createElement('p');
        messageContent.textContent = text;
        messageDiv.appendChild(messageContent);

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function addTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot-message typing-indicator';
        typingDiv.innerHTML = '<p>AI is thinking...</p>';

        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        return typingDiv;
    }

    // Load chat history if available
    loadChatHistory();

    function loadChatHistory() {
        // This would load previous conversations from the database
        // For now, just show welcome message
        if (chatMessages.children.length === 0) {
            addMessage('Hello! I\'m your AI career coach. How can I help you today?', 'bot');
        }
    }
});
