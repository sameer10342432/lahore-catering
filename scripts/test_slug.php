<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Page.php';
require_once __DIR__ . '/../models/Redirect.php';

$slug = 'pakistaanse-specialiteiten-friesland';
$post = Post::findBySlug($slug);
echo "Post found: " . ($post ? $post['title'] : 'NO') . "\n";

$redirect = Redirect::match('/blog/');
echo "Redirect for /blog/: " . ($redirect ? $redirect['target_url'] : 'NONE') . "\n";

$redirect2 = Redirect::match('/buffet/');
echo "Redirect for /buffet/: " . ($redirect2 ? $redirect2['target_url'] : 'NONE') . "\n";
