<?php

use \ProjectOnlineShop\Core\LoggerFactory;
use ProjectOnlineShop\Core\Config;
use ProjectOnlineShop\Exceptions\ConfigNotFound;
use ProjectOnlineShop\Exceptions\ConfigMissingKeyException;
use \ProjectOnlineShop\Controllers\ErrorController;

date_default_timezone_set('Europe/Moscow');

const ROOT_DIR = __DIR__ . "/..";

//require_once __DIR__ . "/../autoload.php"; старый autoloader
require_once ROOT_DIR . "/vendor/autoload.php";

$logger = LoggerFactory::getLogger();
$router = require ROOT_DIR . "/routes/routes.php";

try {
    Config::load(ROOT_DIR);
    $logger->info("Конфиг загружен");
} catch (ConfigNotFound $e) {
    $logger->error($e->getMessage());
    new ErrorController()->internalError(); ///TODO Статически переделать
    exit;
} catch (ConfigMissingKeyException $e) {
    $logger->error($e->getMessage());
    new ErrorController()->internalError();
    exit;
}



