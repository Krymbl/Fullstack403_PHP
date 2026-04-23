<?php

namespace ProjectOnlineShop\Core\Loggers;

use Monolog\Logger;

abstract class AbstractLoggerFactory
{

    protected static array $loggers = [];

    protected function __construct() {}

    protected function __clone() {}

    public static function getLogger(): Logger {
        $class = static::class;
        if (!isset(self::$loggers[$class])) {
            $currentClass = new $class();
            self::$loggers[$class] = $currentClass->createLogger();
        }
        return self::$loggers[$class];
    }

    protected static abstract function createLogger(): Logger;


}