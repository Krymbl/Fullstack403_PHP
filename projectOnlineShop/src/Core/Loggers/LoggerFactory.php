<?php
//
//namespace ProjectOnlineShop\Core\Loggers;
//
//use Monolog\Handler\StreamHandler;
//use Monolog\Logger;
//
//class LoggerFactory
//{
//
//    private static ?Logger $appLogger = null;
//
//    private function __construct()
//    {
//    }
//
//    private function __clone()
//    {
//    }
//
//    public static function getAppLogger(): Logger
//    {
//        $name = "app";
//        $fileName = "app.log";
//        if (self::$appLogger === null) {
//            self::$appLogger = self::createLogger($name, $fileName);
//        }
//        return self::$appLogger;
//    }
//
//    private static function createLogger(string $name, string $fileName): Logger
//    {
//        $logger = new Logger($name);
//        $logger->pushHandler(new StreamHandler(ROOT_DIR . "/logs/$fileName"));
//        $logger->pushHandler(new StreamHandler('php://stdout'));
//
//        return $logger;
//    }
//
//}