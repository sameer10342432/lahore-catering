<?php
$dir = __DIR__ . '/../httpdocs/wp-content/uploads/rank-math';
foreach (glob("$dir/*.xml") as $file) {
    $xml = simplexml_load_file($file);
    if ($xml->getName() === 'urlset') {
        echo "=== File: " . basename($file) . " (Count: " . count($xml->url) . ") ===\n";
        $i = 0;
        foreach ($xml->url as $url) {
            echo "  - " . (string)$url->loc . "\n";
            $i++;
            if ($i >= 5 && count($xml->url) > 7) {
                echo "  ... and " . (count($xml->url) - 5) . " more\n";
                break;
            }
        }
    }
}
