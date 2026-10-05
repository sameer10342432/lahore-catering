<?php
require_once __DIR__ . '/Database.php';

class Page {
    public static function findBySlug(string $slug): ?array {
        return Database::fetch("SELECT * FROM pages WHERE slug = ? AND status IN ('publish', 'published')", [$slug]);
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM pages WHERE id = ?", [$id]);
    }

    public static function all(string $status = 'all'): array {
        if ($status === 'all') {
            return Database::fetchAll("SELECT * FROM pages ORDER BY id ASC");
        }
        return Database::fetchAll("SELECT * FROM pages WHERE status IN ('publish', 'published') ORDER BY id ASC");
    }

    public static function count(): int {
        return (int)Database::fetch("SELECT COUNT(*) as c FROM pages")['c'];
    }

    public static function create(array $data): int {
        $now = date('Y-m-d H:i:s');
        Database::query(
            "INSERT INTO pages (title, slug, content, excerpt, featured_image, featured_image_alt, template, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['title'],
                $data['slug'],
                $data['content'] ?? '',
                $data['excerpt'] ?? '',
                $data['featured_image'] ?? null,
                $data['featured_image_alt'] ?? null,
                $data['template'] ?? 'default',
                $data['status'] ?? 'published',
                $now,
                $now
            ]
        );
        return (int)Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $now = date('Y-m-d H:i:s');
        Database::query(
            "UPDATE pages SET title = ?, slug = ?, content = ?, excerpt = ?, featured_image = ?, featured_image_alt = ?, template = ?, status = ?, updated_at = ? WHERE id = ?",
            [
                $data['title'],
                $data['slug'],
                $data['content'] ?? '',
                $data['excerpt'] ?? '',
                $data['featured_image'] ?? null,
                $data['featured_image_alt'] ?? null,
                $data['template'] ?? 'default',
                $data['status'] ?? 'published',
                $now,
                $id
            ]
        );
        return true;
    }

    public static function delete(int $id): bool {
        Database::query("DELETE FROM pages WHERE id = ?", [$id]);
        Database::query("DELETE FROM seo_metadata WHERE entity_type = 'page' AND entity_id = ?", [$id]);
        return true;
    }
}
