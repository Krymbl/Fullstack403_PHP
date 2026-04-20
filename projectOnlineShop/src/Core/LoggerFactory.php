<?php

namespace ProjectOnlineShop\Core;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerFactory
{

    private static ?Logger $logger = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getLogger(): Logger
    {
        if (self::$logger === null) {
            self::$logger = self::createLogger();
        }
        return self::$logger;
    }

    private static function createLogger(string $name = "app"): Logger
    {
        $logger = new Logger("app");
        $logger->pushHandler(new StreamHandler(ROOT_DIR . "/logs/app.log"));
        $logger->pushHandler(new StreamHandler('php://stdout'));

        return $logger;
    }

}