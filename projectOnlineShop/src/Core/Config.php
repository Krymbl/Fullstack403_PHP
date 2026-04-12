<?php

namespace ProjectOnlineShop\Core;

use Dotenv\Dotenv;
use ProjectOnlineShop\Exceptions\ConfigMissingKeyException;
use ProjectOnlineShop\Exceptions\ConfigNotFound;

class Config
{

    private static array $requiredKeys = [
        'APP_NAME',
        'APP_ENV',
        'APP_DEBUG',
        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASS',
    ];

    public static function load(string $rootDir): void
    {

        if (!file_exists($rootDir . "/.env")) {
            throw new ConfigNotFound($rootDir);
        }

        $dotenv = Dotenv::createImmutable($rootDir);
        $dotenv->load();

        self::validate();
    }

    private static function validate(): void {
        $missingKeys = [];
        foreach (self::$requiredKeys as $key) {
            if (empty($_ENV[$key])) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            throw new ConfigMissingKeyException(implode(", ", $missingKeys));
        }
    }
}