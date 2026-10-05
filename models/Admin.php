<?php
require_once __DIR__ . '/Database.php';

class Admin {
    public static function authenticate(string $username, string $password): ?array {
        $admin = Database::fetch("SELECT * FROM admins WHERE username = ? OR email = ?", [$username, $username]);
        if ($admin && password_verify($password, $admin['password_hash'])) {
            Database::query("UPDATE admins SET last_login = ? WHERE id = ?", [date('Y-m-d H:i:s'), $admin['id']]);
            return $admin;
        }
        return null;
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT id, username, email, role, last_login, created_at FROM admins WHERE id = ?", [$id]);
    }

    public static function updatePassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        Database::query("UPDATE admins SET password_hash = ? WHERE id = ?", [$hash, $id]);
        return true;
    }

    public static function isLoggedIn(): bool {
        return !empty($_SESSION['admin_user_id']);
    }

    public static function current(): ?array {
        if (!self::isLoggedIn()) return null;
        return self::findById((int)$_SESSION['admin_user_id']);
    }

    public static function login(array $admin): void {
        $_SESSION['admin_user_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_logged_at'] = time();
    }

    public static function logout(): void {
        unset($_SESSION['admin_user_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_logged_at']);
    }
}
