<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';

$foodSitemapFile = __DIR__ . '/../httpdocs/wp-content/uploads/rank-math/rank_math_fcaddd3f6fe0a9ac940e6c0b01aa038c.xml';
$xml = simplexml_load_file($foodSitemapFile);

$db = Database::getInstance();
$stmt = $db->prepare("INSERT OR REPLACE INTO food_items (name, slug, description, image_url, image_alt, category) VALUES (?, ?, ?, ?, ?, ?)");

$count = 0;
foreach ($xml->url as $urlObj) {
    $loc = (string)$urlObj->loc;
    $slug = basename(rtrim($loc, '/'));
    $name = ucwords(str_replace('-', ' ', $slug));
    
    // Categorize based on keywords in name
    $category = 'Hoofdgerechten';
    if (preg_match('/lassi|drink|tea|chai/i', $slug)) $category = 'Dranken';
    elseif (preg_match('/kheer|kulfi|jamun|halwa|dessert/i', $slug)) $category = 'Desserts';
    elseif (preg_match('/roti|naan|paratha|bread/i', $slug)) $category = 'Brood & Bijgerechten';
    elseif (preg_match('/samosa|pakora|starter|soup/i', $slug)) $category = 'Voorgerechten';
    elseif (preg_match('/biryani|rice/i', $slug)) $category = 'Biryani & Rijst';

    $imgUrl = null;
    $imgAlt = $name;
    foreach ($urlObj->children('http://www.google.com/schemas/sitemap-image/1.1')->image as $img) {
        $imgUrl = (string)$img->loc;
        $imgAlt = (string)$img->title ?: $name;
        break;
    }

    $desc = "Heerlijke authentieke $name bereid met traditionele kruiden en verse ingrediënten uit de Pakistaanse keuken.";

    $stmt->execute([$name, $slug, $desc, $imgUrl, $imgAlt, $category]);
    $count++;
}

echo "Successfully extracted and inserted $count food items!\n";
