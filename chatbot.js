document.getElementById('send-button').addEventListener('click', sendMessage);

function sendMessage() {
    const userInput = document.getElementById('user-input').value;
    if (userInput.trim() === "") return;

    const outputDiv = document.getElementById('output');
    outputDiv.innerHTML += `<p><strong>You:</strong> ${userInput}</p>`;
    document.getElementById('user-input').value = "";

    fetch('chatbot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `message=${encodeURIComponent(userInput)}`,
    })
    .then(response => response.json())
    .then(data => {
        outputDiv.innerHTML += `<p><strong>Bot:</strong> ${data.reply}</p>`;
        outputDiv.scrollTop = outputDiv.scrollHeight;
    })
    .catch(error => {
        outputDiv.innerHTML += `<p><strong>Error:</strong> Unable to connect to the chatbot.</p>`;
    });
}
