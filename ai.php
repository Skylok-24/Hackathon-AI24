<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Only process if this is a POST request from the chat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    // Sanitize input
    $userMessage = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    // Function to send requests to your local API or any API
    function send($endPoint, $method, $data = null) {
        $url = "http://127.0.0.1:8000/" . $endPoint;
        $headers = ["Content-Type: application/json"];

        $curl = curl_init();
        if (is_array($data)) {
            $data = json_encode($data);
        }

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $data
        ]);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            return json_encode(['error' => curl_error($curl)]);
        }
        curl_close($curl);

        return $response;
    }

    // Send the message to API
    $openaiResponse = send('chatbotagent/', 'POST', ["question" => $userMessage]);
    $responseData = json_decode($openaiResponse, true);

    // Prepare response
    $aiMessage = "Sorry, I couldn't process your request.";
    if (isset($responseData['answer'])) {
        $aiMessage = $responseData['answer'];
    } else if (isset($responseData['error'])) {
        $aiMessage = "Error: " . $responseData['error'];
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(["message" => $aiMessage]);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #0f172a;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 80px;
            background-color: #ffffff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-right: 1px solid #e2e8f0;
        }

        .logo {
            margin-bottom: 30px;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        .nav-tabs {
            display: flex;
            gap: 15px;
        }

        .nav-tab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            color: #0f172a;
            background-color: #f1f5f9;
        }

        .nav-tab:hover {
            background-color: #e2e8f0;
        }

        .nav-tab.active {
            background-color: #7b7ff6;
            color: white;
        }

        .right-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: #f8fafc;
            border-radius: 20px;
            padding: 8px 16px;
            width: 250px;
        }

        .search-bar input {
            border: none;
            background: transparent;
            margin-left: 8px;
            outline: none;
            width: 100%;
            font-size: 14px;
            color: #64748b;
        }

        .notification-icon {
            cursor: pointer;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-pic {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .username {
            font-size: 14px;
            font-weight: 500;
        }

        .role {
            font-size: 12px;
            color: #64748b;
        }

        .content {
            display: flex;
            flex: 1;
            padding: 20px;
            gap: 20px;
        }

        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .greeting-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .greeting {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .hello {
            color: #7b7ff6;
        }

        .greeting-subtitle {
            font-size: 14px;
            color: #64748b;
        }

        .content-creation {
            display: flex;
            flex-direction: column;
            gap: 20px;
            background-color: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .image-upload {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .upload-area {
            height: 200px;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            background-color: #f8f8f8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            color: #0f172a;
            font-size: 14px;
        }

        .ai-button {
            align-self: flex-end;
            background-color: #7b7ff6;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .ai-button:hover {
            background-color: #6366f1;
        }

        .text-input {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .text-input textarea {
            height: 200px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #f8f8f8;
            padding: 15px;
            resize: none;
            outline: none;
            font-size: 14px;
        }

        .publish-button {
            background-color: #00d26a;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            text-align: center;
        }

        .publish-button:hover {
            background-color: #00b85c;
        }

        .right-panel {
            width: 40%;
            max-width: 500px;
        }

        .chat-container {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(236, 246, 255, 0.8) 100%);
            border-radius: 16px;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.4;
        }

        .message.incoming {
            align-self: flex-start;
            background-color: #f1f5f9;
        }

        .message.outgoing {
            align-self: flex-end;
            background-color: #7b7ff6;
            color: white;
        }

        .message-input {
            display: flex;
            padding: 15px;
            border-top: 1px solid #e2e8f0;
        }

        .message-input input {
            flex: 1;
            border: 1px solid #cbd5e1;
            border-radius: 24px;
            padding: 12px 20px;
            outline: none;
            font-size: 14px;
        }

        .message-input button {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #000000;
            border: none;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .message-input button:hover {
            background-color: #1e293b;
        }

        /* Notification Popup Styles */
        .notification-popup {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 300px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transform: translateY(150%);
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .notification-popup.show {
            transform: translateY(0);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background-color: #7b7ff6;
            color: white;
            font-weight: 500;
        }

        .notification-header button {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .notification-content {
            padding: 16px;
            font-size: 14px;
        }

        /* Loading spinner */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="20" fill="#F5F5F5"/>
                <path d="M20 10L20 30M10 20L30 20" stroke="#0A1172" stroke-width="6" stroke-linecap="round"/>
            </svg>
        </div>
    </div>

    <div class="main-content">
        <div class="navbar">
            <div class="nav-tabs">
                <div class="nav-tab">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7.5L10 2.5L17 7.5V16.25C17 16.7141 16.8156 17.1592 16.4874 17.4874C16.1592 17.8156 15.7141 18 15.25 18H4.75C4.28587 18 3.84075 17.8156 3.51256 17.4874C3.18437 17.1592 3 16.7141 3 16.25V7.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dachbord
                </div>
                <div class="nav-tab">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.25 6.875V16.875H3.75V6.875M17.5 3.125H2.5V6.875H17.5V3.125Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Analysis
                </div>
                <div class="nav-tab active">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    AI Twintelli
                </div>
            </div>

            <div class="right-nav">
                <div class="search-bar">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.16667 15.8333C12.8486 15.8333 15.8333 12.8486 15.8333 9.16667C15.8333 5.48477 12.8486 2.5 9.16667 2.5C5.48477 2.5 2.5 5.48477 2.5 9.16667C2.5 12.8486 5.48477 15.8333 9.16667 15.8333Z" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.5 17.5L13.875 13.875" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <input type="text" placeholder="Search" >
                </div>

                <div class="notification-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 8C18 6.4087 17.3679 4.88258 16.2426 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.88258 2.63214 7.75736 3.75736C6.63214 4.88258 6 6.4087 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.73 21C13.5542 21.3031 13.3019 21.5547 12.9982 21.7295C12.6946 21.9044 12.3504 21.9965 12 21.9965C11.6496 21.9965 11.3054 21.9044 11.0018 21.7295C10.6982 21.5547 10.4458 21.3031 10.27 21" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <div class="user-profile">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Desktop%20-%207%20%283%29-YQhHskay9vr5oxjZ0lmTJrinvUzH3V.png" alt="Profile" class="profile-pic">
                    <div class="user-info">
                        <div class="username">ch.oussama</div>
                        <div class="role">Admin</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="left-panel">
                <div class="greeting-card">
                    <div class="greeting">
                        <span class="hello">Hello, </span>
                        <span class="name">oussama</span>
                    </div>
                    <div class="greeting-subtitle">Here's what's happening with your store today.</div>
                </div>

                <div class="content-creation">
                    <div class="image-upload">
                        <div class="upload-area">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 15V3M12 3L7 8M12 3L17 8" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17 21H7C5.89543 21 5 20.1046 5 19V15" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div>Insert image</div>
                        </div>
                        <button class="ai-button">Generation by AI</button>
                    </div>

                    <div class="text-input">
                        <textarea placeholder="what do think"></textarea>
                        <button class="ai-button">Writing by AI</button>
                    </div>

                    <button class="publish-button">To Publish</button>
                </div>
            </div>

            <div class="right-panel">
                <div class="chat-container">
                    <div class="messages" id="messageContainer">
                        <!-- Messages will be added here dynamically -->
                        <div class="message incoming">
                            Hello! How can I assist you today?
                        </div>
                    </div>
                    <form id="chatForm" method="post" class="message-input">
                        <input type="text" id="messageInput" name="message" placeholder="Do you want a content book?">
                        <button id="sendButton" type="submit">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" id="sendIcon">
                                <path d="M22 2L11 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div id="loadingSpinner" class="loading" style="display: none;"></div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="notification-popup" id="notificationPopup">
    <div class="notification-header">
        <span>New Message</span>
        <button id="closeNotification">×</button>
    </div>
    <div class="notification-content" id="notificationContent">
        <!-- Notification content will be added here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const messageContainer = document.getElementById('messageContainer');
        const sendIcon = document.getElementById('sendIcon');
        const loadingSpinner = document.getElementById('loadingSpinner');

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

        // Handle form submission for chat
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });

        // Function to send a message to OpenAI API
        function sendMessage() {
            const message = messageInput.value.trim();
            if (message) {
                // Add user message to chat
                addMessage(message, true);
                messageInput.value = '';

                // Show loading spinner
                sendIcon.style.display = 'none';
                loadingSpinner.style.display = 'block';

                // Disable the send button while processing
                sendButton.disabled = true;

                // Send request to our PHP handler
                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'message=' + encodeURIComponent(message)
                })
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading spinner
                        sendIcon.style.display = 'block';
                        loadingSpinner.style.display = 'none';
                        sendButton.disabled = false;

                        // Add AI response to chat
                        if (data.message) {
                            addMessage(data.message);
                        } else {
                            addMessage("Sorry, I couldn't process your request.");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        sendIcon.style.display = 'block';
                        loadingSpinner.style.display = 'none';
                        sendButton.disabled = false;
                        addMessage("Sorry, there was an error processing your request.");
                    });
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
</script>
</body>
</html>