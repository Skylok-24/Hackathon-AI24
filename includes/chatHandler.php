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
