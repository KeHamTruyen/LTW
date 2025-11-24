<?php
class Database {
    private static $pdo = null;
    public static function get() {
        if (self::$pdo === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $opt);
        }
        return self::$pdo;
    }
}
