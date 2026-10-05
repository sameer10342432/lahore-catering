<?php
/**
 * Database Connection Manager (PDO)
 */

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../config/database.php';
            $driver = $config['driver'];

            try {
                if ($driver === 'mysql') {
                    $c = $config['mysql'];
                    $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset={$c['charset']}";
                    self::$instance = new PDO($dsn, $c['username'], $c['password'], $c['options']);
                } else {
                    $c = $config['sqlite'];
                    $dbDir = dirname($c['path']);
                    if (!is_dir($dbDir)) {
                        mkdir($dbDir, 0777, true);
                    }
                    self::$instance = new PDO("sqlite:" . $c['path'], null, null, $c['options']);
                    // Enable WAL mode & foreign keys for SQLite
                    self::$instance->exec("PRAGMA journal_mode = WAL; PRAGMA foreign_keys = ON;");
                }
            } catch (PDOException $e) {
                // If MySQL fails, fallback gracefully to SQLite if file exists
                if ($driver === 'mysql' && file_exists($config['sqlite']['path'])) {
                    self::$instance = new PDO("sqlite:" . $config['sqlite']['path'], null, null, $config['sqlite']['options']);
                    self::$instance->exec("PRAGMA journal_mode = WAL; PRAGMA foreign_keys = ON;");
                } else {
                    die("Database connection error: " . $e->getMessage());
                }
            }
        }
        return self::$instance;
    }

    public static function query(string $sql, array $params = []): PDOStatement {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetch(string $sql, array $params = []): ?array {
        $res = self::query($sql, $params)->fetch();
        return $res === false ? null : $res;
    }

    public static function lastInsertId(): string {
        return self::getInstance()->lastInsertId();
    }
}
