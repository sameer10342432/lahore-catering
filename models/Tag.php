<?php
require_once __DIR__ . '/Database.php';

class Tag {
    public static function all(): array {
        return Database::fetchAll("SELECT t.*, (SELECT COUNT(*) FROM post_tags pt JOIN posts p ON pt.post_id = p.id WHERE pt.tag_id = t.id AND p.status = 'published') as post_count FROM tags t ORDER BY t.name ASC");
    }

    public static function findBySlug(string $slug): ?array {
        return Database::fetch("SELECT * FROM tags WHERE slug = ?", [$slug]);
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM tags WHERE id = ?", [$id]);
    }

    public static function create(string $name, string $slug): int {
        Database::query("INSERT INTO tags (name, slug) VALUES (?, ?)", [$name, $slug]);
        return (int)Database::lastInsertId();
    }

    public static function update(int $id, string $name, string $slug): bool {
        Database::query("UPDATE tags SET name = ?, slug = ? WHERE id = ?", [$name, $slug, $id]);
        return true;
    }

    public static function delete(int $id): bool {
        Database::query("DELETE FROM tags WHERE id = ?", [$id]);
        Database::query("DELETE FROM post_tags WHERE tag_id = ?", [$id]);
        return true;
    }
}
