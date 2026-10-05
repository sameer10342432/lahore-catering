<?php
require_once __DIR__ . '/Database.php';

class FoodItem {
    public static function all(): array {
        return Database::fetchAll("SELECT * FROM food_items ORDER BY category ASC, name ASC");
    }

    public static function getByCategory(string $category): array {
        return Database::fetchAll("SELECT * FROM food_items WHERE category = ? ORDER BY name ASC", [$category]);
    }

    public static function getCategories(): array {
        $rows = Database::fetchAll("SELECT DISTINCT category FROM food_items ORDER BY category ASC");
        return array_column($rows, 'category');
    }

    public static function findBySlug(string $slug): ?array {
        return Database::fetch("SELECT * FROM food_items WHERE slug = ?", [$slug]);
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM food_items WHERE id = ?", [$id]);
    }
}
