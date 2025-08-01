<?php
$question = $_POST['question'] ?? '';
$plant = $_POST['plant'] ?? '';

// Ambil API Key dari environment
$apiKey = getenv('OPENROUTER_API_KEY');

if (trim($question) === '') {
    echo "Pertanyaan tidak boleh kosong.";
    exit;
}

$data = [
    "model" => "openai/gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => "Kamu adalah asisten ahli tanaman. Jawab dalam bahasa Indonesia dengan jelas dan ramah."],
        ["role" => "user", "content" => "Tanaman yang sedang dibahas bernama: $plant. Pertanyaanku: $question"]
    ]
];

$ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && $response !== false) {
    $result = json_decode($response, true);
    $answer = $result['choices'][0]['message']['content'] ?? 'Tidak ada jawaban dari AI.';
    echo nl2br(htmlspecialchars($answer));
} else {
    echo "❌ Gagal menghubungi AI.";
}
