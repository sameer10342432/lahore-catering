<?php
require_once __DIR__ . '/Database.php';

class Category {
    public static function all(): array {
        return Database::fetchAll("SELECT c.*, (SELECT COUNT(*) FROM post_categories pc JOIN posts p ON pc.post_id = p.id WHERE pc.category_id = c.id AND p.status = 'published') as post_count FROM categories c ORDER BY c.name ASC");
    }

    public static function findBySlug(string $slug): ?array {
        return Database::fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM categories WHERE id = ?", [$id]);
    }

    public static function create(string $name, string $slug, string $description = '', int $parentId = 0): int {
        Database::query("INSERT INTO categories (name, slug, description, parent_id) VALUES (?, ?, ?, ?)", [$name, $slug, $description, $parentId]);
        return (int)Database::lastInsertId();
    }

    public static function update(int $id, string $name, string $slug, string $description = '', int $parentId = 0): bool {
        Database::query("UPDATE categories SET name = ?, slug = ?, description = ?, parent_id = ? WHERE id = ?", [$name, $slug, $description, $parentId, $id]);
        return true;
    }

    public static function delete(int $id): bool {
        Database::query("DELETE FROM categories WHERE id = ?", [$id]);
        Database::query("DELETE FROM post_categories WHERE category_id = ?", [$id]);
        return true;
    }
}
