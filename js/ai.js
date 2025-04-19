document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const messageContainer = document.getElementById('messageContainer');
    const notificationPopup = document.getElementById('notificationPopup');
    const notificationContent = document.getElementById('notificationContent');
    const closeNotification = document.getElementById('closeNotification');

    // Function to add a message to the chat
    function addMessage(text, isOutgoing = false) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.classList.add(isOutgoing ? 'outgoing' : 'incoming');
        messageElement.textContent = text;
        messageContainer.appendChild(messageElement);
        messageContainer.scrollTop = messageContainer.scrollHeight;

        // If it's an incoming message, show notification
        if (!isOutgoing) {
            showNotification(text);
        }
    }

    // Function to show notification popup
    function showNotification(text) {
        notificationContent.textContent = text;
        notificationPopup.classList.add('show');

        // Auto hide notification after 5 seconds
        setTimeout(() => {
            notificationPopup.classList.remove('show');
        }, 5000);
    }

    // Close notification when clicking the close button
    closeNotification.addEventListener('click', function() {
        notificationPopup.classList.remove('show');
    });

    // Send message when clicking the send button
    sendButton.addEventListener('click', function() {
        sendMessage();
    });

    // Send message when pressing Enter key
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Function to send a message
    function sendMessage() {
        const message = messageInput.value.trim();
        if (message) {
            addMessage(message, true);
            messageInput.value = '';

            // Simulate response after a short delay
            setTimeout(() => {
                addMessage("Thank you for your message. Would you like me to help you create a content book?");
            }, 1000);
        }
    }

    // Image upload functionality
    const uploadArea = document.querySelector('.upload-area');
    uploadArea.addEventListener('click', function() {
        alert('Click to upload an image');
    });

    // AI generation buttons
    const aiButtons = document.querySelectorAll('.ai-button');
    aiButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = button.textContent.includes('Generation') ? 'image' : 'text';
            showNotification(`AI is generating ${type} content...`);

            setTimeout(() => {
                if (type === 'image') {
                    alert('AI has generated an image');
                } else {
                    const textarea = document.querySelector('textarea');
                    textarea.value = "Here's some AI-generated content for your store. This content is designed to engage your customers and highlight your products effectively.";
                }
            }, 2000);
        });
    });

    // Publish button
    const publishButton = document.querySelector('.publish-button');
    publishButton.addEventListener('click', function() {
        showNotification("Content published successfully!");
    });
});