<?php

namespace ProjectOnlineShop\Core;

use Monolog\Logger;
use PDO;
use PDOException;
use ProjectOnlineShop\Exceptions\ConnectionException;

class Database
{
    private static ?PDO $connection = null;
    private static ?Logger $logger = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getConnection(): PDO
    {
        if (self::$logger === null) {
            self::$logger = LoggerFactory::getLogger();
        }
        if (self::$connection === null) {
            self::$connection = self::connect();
        }
        return self::$connection;
    }

    private static function connect(): PDO
    {
        $host = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];
        try {
            $connection = new PDO("pgsql:host=$host;dbname=$dbName;",
                $username, $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            self::$logger->info("Успешное подключение к базе данных: $dbName");
            return $connection;
        } catch (PDOException $e) {
            self::$logger->error("Ошибка при подключении к базе данных: " . $e->getMessage());
            throw new ConnectionException();
        }

    }

    public static function closeConnection(): void {
        self::$connection = null;
    }
}