<?php
$context = stream_context_create([
    'http' => [
        'timeout' => 15,
        'user_agent' => 'Mozilla/5.0'
    ]
]);

$types = json_decode(@file_get_contents('https://lahorecatering.nl/wp-json/wp/v2/types', false, $context), true);
echo "=== POST TYPES ===\n";
foreach ($types as $key => $type) {
    echo "- $key: " . ($type['name'] ?? '') . " (rest_base: " . ($type['rest_base'] ?? '') . ")\n";
}

// Check total counts from headers for each post type
foreach (['posts', 'pages', 'media', 'categories', 'tags'] as $endpoint) {
    $headers = get_headers("https://lahorecatering.nl/wp-json/wp/v2/$endpoint?per_page=1", true);
    $total = $headers['X-WP-Total'] ?? $headers['x-wp-total'] ?? 'unknown';
    echo "Total $endpoint: $total\n";
}

// Also check custom types if available in REST
foreach ($types as $key => $type) {
    $rest_base = $type['rest_base'] ?? '';
    if (!empty($rest_base) && !in_array($rest_base, ['posts', 'pages', 'media', 'blocks', 'navigation'])) {
        $headers = @get_headers("https://lahorecatering.nl/wp-json/wp/v2/$rest_base?per_page=1", true);
        $total = $headers['X-WP-Total'] ?? $headers['x-wp-total'] ?? 'not in rest';
        echo "Total $rest_base ($key): $total\n";
    }
}
