<?php
require_once __DIR__ . '/Database.php';

class FormSubmission {
    public static function create(string $formType, array $data, ?string $ip = null, ?string $ua = null, int $formId = 1): int {
        $now = date('Y-m-d H:i:s');
        Database::query(
            "INSERT INTO form_submissions (form_id, form_type, data_json, ip_address, user_agent, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', ?)",
            [$formId, $formType, json_encode($data, JSON_UNESCAPED_UNICODE), $ip, $ua, $now]
        );
        return (int)Database::lastInsertId();
    }

    public static function all(int $limit = 50, int $offset = 0): array {
        return Database::fetchAll("SELECT * FROM form_submissions ORDER BY created_at DESC LIMIT ? OFFSET ?", [$limit, $offset]);
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM form_submissions WHERE id = ?", [$id]);
    }

    public static function markStatus(int $id, string $status): bool {
        Database::query("UPDATE form_submissions SET status = ? WHERE id = ?", [$status, $id]);
        return true;
    }

    public static function delete(int $id): bool {
        Database::query("DELETE FROM form_submissions WHERE id = ?", [$id]);
        return true;
    }

    public static function countNew(): int {
        return (int)Database::fetch("SELECT COUNT(*) as c FROM form_submissions WHERE status = 'new'")['c'];
    }
}
