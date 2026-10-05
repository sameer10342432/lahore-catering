<?php
require_once __DIR__ . '/Database.php';

class Media {
    public static function all(int $limit = 50, int $offset = 0): array {
        return Database::fetchAll("SELECT * FROM media ORDER BY id DESC LIMIT ? OFFSET ?", [$limit, $offset]);
    }

    public static function count(): int {
        return (int)Database::fetch("SELECT COUNT(*) as c FROM media")['c'];
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM media WHERE id = ?", [$id]);
    }

    public static function findByFilename(string $filename): ?array {
        return Database::fetch("SELECT * FROM media WHERE filename = ? LIMIT 1", [$filename]);
    }

    public static function create(array $data): int {
        $now = date('Y-m-d H:i:s');
        Database::query(
            "INSERT INTO media (filename, filepath, url, alt_text, caption, mime_type, file_size, width, height, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['filename'],
                $data['filepath'],
                $data['url'],
                $data['alt_text'] ?? '',
                $data['caption'] ?? '',
                $data['mime_type'] ?? '',
                $data['file_size'] ?? 0,
                $data['width'] ?? 0,
                $data['height'] ?? 0,
                $now
            ]
        );
        return (int)Database::lastInsertId();
    }

    public static function updateAlt(int $id, string $altText, string $caption = ''): bool {
        Database::query("UPDATE media SET alt_text = ?, caption = ? WHERE id = ?", [$altText, $caption, $id]);
        return true;
    }

    public static function delete(int $id): bool {
        $media = self::findById($id);
        if ($media) {
            $absPath = ROOT_PATH . '/' . $media['filepath'];
            if (file_exists($absPath)) {
                @unlink($absPath);
            }
            Database::query("DELETE FROM media WHERE id = ?", [$id]);
        }
        return true;
    }
}
