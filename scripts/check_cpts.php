<?php
$cpts = ['bucket', 'testimonial', 'food', 'recipe', 'gallery', 'erm_menu', 'bwg_gallery', 'menu', 'menus'];
$results = [];

foreach ($cpts as $cpt) {
    $ch = curl_init("https://lahorecatering.nl/wp-json/wp/v2/$cpt?per_page=1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($http_code === 200) {
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $header_size);
        if (preg_match('/X-WP-Total:\s*(\d+)/i', $headers, $m)) {
            $results[$cpt] = (int)$m[1];
        } else {
            $results[$cpt] = '200 OK (no header)';
        }
    } else {
        $results[$cpt] = "HTTP $http_code";
    }
    curl_close($ch);
}

echo json_encode($results, JSON_PRETTY_PRINT) . "\n";
