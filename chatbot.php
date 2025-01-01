<?php
header('Content-Type: application/json');

$apiKey = "API Key"; // Replace with your actual API key
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent?key=$apiKey";

$userMessage = $_POST['message'] ?? '';

$payload = [
    "prompt" => [
        "contents" => [
            [
                "role" => "user",
                "parts" => [["text" => $userMessage]]
            ]
        ]
    ],
    "generationConfig" => [
        "temperature" => 1,
        "topK" => 40,
        "topP" => 0.95,
        "maxOutputTokens" => 8192
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['reply' => 'Error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$responseData = json_decode($response, true);
file_put_contents('debug_log.txt', $response); // Debugging: log response

// Adjust based on actual response structure
$botReply = $responseData['predictions'][0]['text'] ?? "I'm sorry, I couldn't understand that.";

echo json_encode(['reply' => $botReply]);
?>
