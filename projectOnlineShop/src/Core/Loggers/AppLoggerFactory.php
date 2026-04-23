<?php

namespace ProjectOnlineShop\Core\Loggers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AppLoggerFactory extends AbstractLoggerFactory
{


    protected static function createLogger(): Logger
    {
        $name = "app";
        $fileName = "app.log";

        $logger = new Logger($name);
        $logger->pushHandler(new StreamHandler(ROOT_DIR . "/logs/$fileName"));
        $logger->pushHandler(new StreamHandler('php://stdout'));

        return $logger;
    }
}