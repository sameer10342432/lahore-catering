<?php
require_once MODEL_PATH . '/Page.php';
require_once MODEL_PATH . '/Post.php';
require_once MODEL_PATH . '/FoodItem.php';
require_once MODEL_PATH . '/Seo.php';

class HomeController {
    public function index(): void {
        $page = Page::findBySlug('home') ?: Page::findById(101);
        $recentPosts = Post::paginate(1, 3)['items'];
        $featuredDishes = FoodItem::all();

        require VIEW_PATH . '/pages/home.php';
    }
}
