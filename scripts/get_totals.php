<?php
$endpoints = ['posts', 'pages', 'media', 'categories', 'tags'];
$results = [];

foreach ($endpoints as $ep) {
    $ch = curl_init("https://lahorecatering.nl/wp-json/wp/v2/$ep?per_page=1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    if ($err) {
        $results[$ep] = 'curl error: ' . $err;
        curl_close($ch);
        continue;
    }
    
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    if (preg_match('/X-WP-Total:\s*(\d+)/i', $headers, $m)) {
        $results[$ep] = (int)$m[1];
    } else {
        $results[$ep] = 'error: ' . substr($headers, 0, 100);
    }
    curl_close($ch);
}

echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
file_put_contents(__DIR__ . '/wp_totals.json', json_encode($results, JSON_PRETTY_PRINT));
