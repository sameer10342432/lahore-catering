<?php
require_once __DIR__ . '/Database.php';

class Redirect {
    public static function match(string $uri): ?array {
        $cleanUri = '/' . trim(parse_url($uri, PHP_URL_PATH), '/');
        if ($cleanUri === '/') {
            return null;
        }

        // Try exact match with and without trailing slash
        $redir = Database::fetch("SELECT * FROM redirects WHERE source_url = ? OR source_url = ?", [
            $cleanUri,
            $cleanUri . '/'
        ]);

        if ($redir) {
            // Guard against redirecting to self
            if (rtrim($redir['target_url'], '/') === rtrim($cleanUri, '/')) {
                return null;
            }
            Database::query("UPDATE redirects SET hits = hits + 1 WHERE id = ?", [$redir['id']]);
        }
        return $redir;
    }

    public static function all(): array {
        return Database::fetchAll("SELECT * FROM redirects ORDER BY hits DESC, created_at DESC");
    }

    public static function create(string $source, string $target, int $status = 301): int {
        $now = date('Y-m-d H:i:s');
        Database::query("INSERT OR REPLACE INTO redirects (source_url, target_url, status_code, created_at) VALUES (?, ?, ?, ?)", [$source, $target, $status, $now]);
        return (int)Database::lastInsertId();
    }

    public static function delete(int $id): bool {
        Database::query("DELETE FROM redirects WHERE id = ?", [$id]);
        return true;
    }
}
