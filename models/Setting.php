<?php
require_once __DIR__ . '/Database.php';

class Setting {
    private static array $cache = [];

    public static function get(string $key, ?string $default = null): ?string {
        if (!isset(self::$cache[$key])) {
            $row = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
            self::$cache[$key] = $row ? $row['setting_value'] : $default;
        }
        return self::$cache[$key];
    }

    public static function all(): array {
        $rows = Database::fetchAll("SELECT setting_key, setting_value, setting_group FROM settings");
        $res = [];
        foreach ($rows as $r) {
            $res[$r['setting_key']] = $r['setting_value'];
        }
        return $res;
    }

    public static function set(string $key, ?string $value, string $group = 'general'): bool {
        self::$cache[$key] = $value;
        $exists = Database::fetch("SELECT id FROM settings WHERE setting_key = ?", [$key]);
        if ($exists) {
            Database::query("UPDATE settings SET setting_value = ?, setting_group = ? WHERE setting_key = ?", [$value, $group, $key]);
        } else {
            Database::query("INSERT INTO settings (setting_key, setting_value, setting_group) VALUES (?, ?, ?)", [$key, $value, $group]);
        }
        return true;
    }
}
