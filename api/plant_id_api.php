<?php
function identify_plant($imagePath) {
    $apiKey = 'k0oufLamGUjv1D32UIkhFb50lRn1OOtC1CQJs9IiN4swu3zlUY'; 
    $realPath = __DIR__ . '/../' . $imagePath;

    if (!file_exists($realPath)) {
        return [
            'name' => 'Tidak ditemukan',
            'description' => 'File gambar tidak ditemukan di server.',
            'image' => ''
        ];
    }

    $imgData = base64_encode(file_get_contents($realPath));
    $data = [
        'images' => [$imgData],
        'organs' => ['leaf'],
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.plant.id/v2/identify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Api-Key: $apiKey"
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($response, true);
    file_put_contents(__DIR__ . '/debug_response.json', json_encode($result, JSON_PRETTY_PRINT));

    $suggestion = $result['suggestions'][0] ?? [];

    $name    = $suggestion['plant_name'] ?? 'Tidak diketahui';
    $sciName = $suggestion['plant_details']['scientific_name'] ?? '';
    $genus   = $suggestion['plant_details']['structured_name']['genus'] ?? '';
    $species = $suggestion['plant_details']['structured_name']['species'] ?? '';
    $hybrid  = $suggestion['plant_details']['structured_name']['hybrid'] ?? '';
    $image   = $suggestion['similar_images'][0]['url'] ?? '';

    $wikiDescription = getWikipediaDescription($sciName);

    if ($wikiDescription) {
        $wikiDescription = translateToIndonesian($wikiDescription);
    } else {
        $wikiDescription = "Tanaman ini diidentifikasi sebagai <strong>$name</strong>.";
        if ($sciName) {
            $wikiDescription .= " Nama ilmiahnya adalah <em>$sciName</em>.";
        }
        if ($genus || $species || $hybrid) {
            $wikiDescription .= " Termasuk genus <em>$genus</em>";
            if ($species) $wikiDescription .= ", spesies <em>$species</em>";
            if ($hybrid)  $wikiDescription .= ", hibrida <em>$hybrid</em>";
            $wikiDescription .= ".";
        }
    }

    return [
        'name' => $name,
        'description' => $wikiDescription,
        'image' => $image
    ];
}

function getWikipediaDescription($term) {
    if (!$term) return null;

    $term = urlencode(str_replace(' ', '_', $term));

    $urlId = "https://id.wikipedia.org/api/rest_v1/page/summary/$term";
    $responseId = @file_get_contents($urlId);
    $dataId = $responseId ? json_decode($responseId, true) : null;

    if (!empty($dataId['extract'])) {
        return $dataId['extract'];
    }

    $urlEn = "https://en.wikipedia.org/api/rest_v1/page/summary/$term";
    $responseEn = @file_get_contents($urlEn);
    $dataEn = $responseEn ? json_decode($responseEn, true) : null;

    if (!empty($dataEn['extract'])) {
        return $dataEn['extract'];
    }

    return null;
}

function translateToIndonesian($text) {
    $encoded = urlencode($text);
    $url = "https://api.mymemory.translated.net/get?q=$encoded&langpair=en|id";

    $response = @file_get_contents($url);
    if (!$response) return $text;

    $data = json_decode($response, true);
    return $data['responseData']['translatedText'] ?? $text;
}
