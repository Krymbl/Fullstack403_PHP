<?php

class Database
{
    private static PDO $connection;
    private static string $databasePath = __DIR__ . "/users.db";
    private function __construct() {
        $isNewDb = !file_exists(self::$databasePath);
        self::$connection = new PDO("sqlite:" . self::$databasePath);
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        if ($isNewDb) {
            self::createDatabase();
        }
    }

    public static function getConnection(): PDO
    {
        if (empty(self::$connection)) {
            new self();
        }
        return self::$connection;
    }

    private static function createDatabase() : void {
        self::$connection->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                age INTEGER NOT NULL
            )
        ");
    }
}