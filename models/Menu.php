<?php
require_once __DIR__ . '/Database.php';

class Menu {
    public static function getByLocation(string $location): array {
        $menu = Database::fetch("SELECT * FROM menus WHERE location = ?", [$location]);
        if (!$menu) return [];
        return self::getItems($menu['id']);
    }

    public static function getItems(int $menuId): array {
        return Database::fetchAll("SELECT * FROM menu_items WHERE menu_id = ? ORDER BY order_index ASC", [$menuId]);
    }

    public static function all(): array {
        return Database::fetchAll("SELECT * FROM menus");
    }

    public static function addItem(int $menuId, string $title, string $url, string $target = '_self', int $order = 0): int {
        Database::query("INSERT INTO menu_items (menu_id, title, url, target, order_index) VALUES (?, ?, ?, ?, ?)", [$menuId, $title, $url, $target, $order]);
        return (int)Database::lastInsertId();
    }

    public static function updateItem(int $id, string $title, string $url, string $target = '_self', int $order = 0): bool {
        Database::query("UPDATE menu_items SET title = ?, url = ?, target = ?, order_index = ? WHERE id = ?", [$title, $url, $target, $order, $id]);
        return true;
    }

    public static function deleteItem(int $id): bool {
        Database::query("DELETE FROM menu_items WHERE id = ?", [$id]);
        return true;
    }
}
