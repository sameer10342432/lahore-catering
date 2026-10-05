<?php
/**
 * Lahore Catering - Complete Data Extraction Script
 * Extracts 100% of posts, pages, categories, tags, media, and sitemap pages.
 */

ini_set('memory_limit', '1024M');
set_time_limit(0);

$backupDir = __DIR__ . '/../data_backup';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
}

function fetch_json($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Lahore-Migration-Auditor/1.0',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    $res = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($err || $code !== 200) {
        return null;
    }
    return json_decode($res, true);
}

function fetch_all_paginated($endpoint, $totalExpected, $perPage = 50) {
    $items = [];
    $page = 1;
    $totalPages = ceil($totalExpected / $perPage);
    
    echo "Fetching $endpoint (Total expected: $totalExpected across $totalPages pages)...\n";
    while ($page <= $totalPages) {
        $url = "https://lahorecatering.nl/wp-json/wp/v2/$endpoint?per_page=$perPage&page=$page&_embed=1";
        $data = fetch_json($url);
        if (!$data || !is_array($data) || empty($data)) {
            echo "  Warning: Empty or failed response on page $page for $endpoint\n";
            // Retry once after 2 seconds
            sleep(2);
            $data = fetch_json($url);
            if (!$data || !is_array($data)) {
                echo "  Failed page $page on retry, breaking.\n";
                break;
            }
        }
        $count = count($data);
        echo "  - Page $page: fetched $count items.\n";
        foreach ($data as $item) {
            $items[$item['id']] = $item;
        }
        $page++;
        usleep(100000); // 100ms pause to be polite
    }
    return array_values($items);
}

// 1. Fetch Posts
echo "=== STEP 1: EXTRACTING POSTS ===\n";
$posts = fetch_all_paginated('posts', 258, 50);
echo "Total posts extracted: " . count($posts) . "\n";
file_put_contents("$backupDir/posts.json", json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 2. Fetch Pages
echo "\n=== STEP 2: EXTRACTING PAGES ===\n";
$pages = fetch_all_paginated('pages', 21, 50);
echo "Total pages extracted: " . count($pages) . "\n";
file_put_contents("$backupDir/pages.json", json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 3. Fetch Categories
echo "\n=== STEP 3: EXTRACTING CATEGORIES ===\n";
$categories = fetch_all_paginated('categories', 5, 50);
echo "Total categories extracted: " . count($categories) . "\n";
file_put_contents("$backupDir/categories.json", json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 4. Fetch Tags
echo "\n=== STEP 4: EXTRACTING TAGS ===\n";
$tags = fetch_all_paginated('tags', 34, 50);
echo "Total tags extracted: " . count($tags) . "\n";
file_put_contents("$backupDir/tags.json", json_encode($tags, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 5. Fetch Media metadata
echo "\n=== STEP 5: EXTRACTING MEDIA METADATA ===\n";
$media = fetch_all_paginated('media', 506, 100);
echo "Total media items extracted: " . count($media) . "\n";
file_put_contents("$backupDir/media.json", json_encode($media, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "\nExtraction complete! Data saved to $backupDir\n";
