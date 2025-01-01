<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Chatbot ❤️</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #ffe8d6, #ffd1ba);
            margin: 0;
            padding: 0;
        }

        .chat-container {
            width: 400px;
            margin: 50px auto;
            background: #fff8f0;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 2px solid #ff6f61;
        }

        #chat-window {
            height: 300px;
            overflow-y: auto;
            padding: 15px;
            border-bottom: 2px solid #ff6f61;
            background: #fff0e4;
        }

        #output {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 0.9rem;
            color: #444;
        }

        #output div {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            background-color: #ffd7c2;
            color: #333;
            width: fit-content;
            max-width: 80%;
        }

        #output .bot {
            background-color: #ffa69e;
            color: #fff;
            align-self: flex-start;
        }

        #output .user {
            background-color: #fdf5f2;
            color: #333;
            align-self: flex-end;
            margin-left: auto;
        }

        .input-container {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #fff8f0;
        }

        #user-input {
            flex: 1;
            padding: 12px;
            font-size: 1rem;
            border: 2px solid #ffd1ba;
            border-radius: 20px;
            outline: none;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #send-button {
            margin-left: 10px;
            padding: 12px 18px;
            background: #ff6f61;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #send-button:hover {
            background: #e65a50;
        }

        /* Scrollbar styling */
        #chat-window::-webkit-scrollbar {
            width: 8px;
        }

        #chat-window::-webkit-scrollbar-thumb {
            background: #ff6f61;
            border-radius: 5px;
        }

        #chat-window::-webkit-scrollbar-thumb:hover {
            background: #e65a50;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div id="chat-window">
            <div id="output"></div>
        </div>
        <div class="input-container">
            <input type="text" id="user-input" placeholder="Type your message here...">
            <button id="send-button">Send</button>
        </div>
    </div>
    <script>
        // Example Chatbot Script
        const outputDiv = document.getElementById('output');
        const userInput = document.getElementById('user-input');
        const sendButton = document.getElementById('send-button');

        sendButton.addEventListener('click', () => {
            const userMessage = userInput.value.trim();
            if (userMessage) {
                // Add user message to chat
                const userDiv = document.createElement('div');
                userDiv.className = 'user';
                userDiv.textContent = userMessage;
                outputDiv.appendChild(userDiv);

                // Simulate bot response
                setTimeout(() => {
                    const botDiv = document.createElement('div');
                    botDiv.className = 'bot';
                    botDiv.textContent = `I love food too! You said: "${userMessage}" 🍴`;
                    outputDiv.appendChild(botDiv);
                    outputDiv.scrollTop = outputDiv.scrollHeight;
                }, 500);

                userInput.value = '';
                outputDiv.scrollTop = outputDiv.scrollHeight;
            }
        });

        // Allow Enter key to send message
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendButton.click();
            }
        });
    </script>
</body>
</html>
