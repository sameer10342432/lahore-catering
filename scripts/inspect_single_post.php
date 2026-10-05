<?php
$ch = curl_init("https://lahorecatering.nl/wp-json/wp/v2/posts?per_page=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
curl_close($ch);

$post = json_decode($res, true)[0] ?? [];
echo "POST KEYS: " . implode(', ', array_keys($post)) . "\n\n";
echo "TITLE: " . ($post['title']['rendered'] ?? '') . "\n";
echo "SLUG: " . ($post['slug'] ?? '') . "\n";
echo "FEATURED MEDIA ID: " . ($post['featured_media'] ?? '') . "\n";
echo "CATEGORIES: " . json_encode($post['categories'] ?? []) . "\n";
echo "TAGS: " . json_encode($post['tags'] ?? []) . "\n";
echo "CONTENT SNIPPET: " . substr(strip_tags($post['content']['rendered'] ?? ''), 0, 200) . "...\n";
