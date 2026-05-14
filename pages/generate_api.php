<?php
session_start();
include '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}

$theme  = clean_input($_POST['theme']  ?? '');
$style  = clean_input($_POST['style']  ?? 'beautiful and emotional');
$length = clean_input($_POST['length'] ?? 'medium (8-12 lines)');

if (empty($theme)) {
    echo json_encode(['error' => 'Please enter a theme for your poem.']);
    exit();
}

// Build a rich prompt that explicitly requests a COMPLETE poem
$prompt = "Write a COMPLETE {$style} poem about '{$theme}'. "
        . "The poem MUST be {$length}. "
        . "Use vivid imagery, metaphors, and emotional depth. "
        . "The poem must have a clear beginning, middle and end — do NOT cut it short. "
        . "Format each line on its own line. Do not include a title. Output ONLY the poem text, nothing else.";

$data = [
    'contents' => [[
        'parts' => [['text' => $prompt]]
    ]],
    'generationConfig' => [
        'temperature'     => 0.9,
        'maxOutputTokens' => 2048,
        'stopSequences'   => [],
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, GEMINI_API_URL . '?key=' . GEMINI_API_KEY);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $err = curl_error($ch);
    curl_close($ch);
    echo json_encode(['error' => 'API connection failed: ' . $err]);
    exit();
}

curl_close($ch);

$result = json_decode($response, true);

if (isset($result['error'])) {
    $msg = $result['error']['message'] ?? 'API error occurred.';
    echo json_encode(['error' => $msg]);
    exit();
}

$poem = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

if (empty($poem)) {
    echo json_encode(['error' => 'No poem was generated. Please try again.']);
    exit();
}

// Clean up any markdown asterisks from Gemini
$poem = preg_replace('/\*{1,2}/', '', $poem);
$poem = trim($poem);

echo json_encode(['success' => true, 'poem' => $poem]);
?>
