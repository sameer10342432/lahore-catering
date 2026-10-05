<?php
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
    ]
]);

echo "Testing REST API...\n";
$endpoints = [
    'posts' => 'https://lahorecatering.nl/wp-json/wp/v2/posts?per_page=5',
    'pages' => 'https://lahorecatering.nl/wp-json/wp/v2/pages?per_page=5',
    'categories' => 'https://lahorecatering.nl/wp-json/wp/v2/categories',
    'tags' => 'https://lahorecatering.nl/wp-json/wp/v2/tags',
    'media' => 'https://lahorecatering.nl/wp-json/wp/v2/media?per_page=5'
];

foreach ($endpoints as $name => $url) {
    $res = @file_get_contents($url, false, $context);
    if ($res === false) {
        echo "Failed to load $name ($url)\n";
    } else {
        $json = json_decode($res, true);
        $count = is_array($json) ? count($json) : 0;
        echo "Success for $name: $count items returned! (Length: " . strlen($res) . " bytes)\n";
    }
}
