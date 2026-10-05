<?php
$ch = curl_init("https://lahorecatering.nl/wp-json/wp/v2/posts?per_page=1&_embed=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
curl_close($ch);

$post = json_decode($res, true)[0] ?? [];
$embedded = $post['_embedded'] ?? [];
echo "EMBEDDED KEYS: " . implode(', ', array_keys($embedded)) . "\n";
if (!empty($embedded['wp:featuredmedia'][0])) {
    $fm = $embedded['wp:featuredmedia'][0];
    echo "FEATURED MEDIA URL: " . ($fm['source_url'] ?? '') . "\n";
    echo "FEATURED MEDIA ALT: " . ($fm['alt_text'] ?? '') . "\n";
}
if (!empty($embedded['wp:term'])) {
    echo "TERMS:\n";
    foreach ($embedded['wp:term'] as $taxList) {
        foreach ($taxList as $term) {
            echo "  - [{$term['taxonomy']}] ID: {$term['id']}, Name: {$term['name']}, Slug: {$term['slug']}\n";
        }
    }
}
