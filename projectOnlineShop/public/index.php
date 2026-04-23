<?php

use ProjectOnlineShop\Controllers\Admin\AddController;
use ProjectOnlineShop\Controllers\Admin\CatalogController as AdminCatalogController;
use ProjectOnlineShop\Controllers\Admin\EditController;
use ProjectOnlineShop\Controllers\ErrorController;
use ProjectOnlineShop\Controllers\User\AuthController;
use ProjectOnlineShop\Controllers\User\CartController;
use ProjectOnlineShop\Controllers\User\CatalogController as UserCatalogController;
use ProjectOnlineShop\Controllers\User\HomeController;
use ProjectOnlineShop\Controllers\User\OrderController;
use ProjectOnlineShop\Controllers\User\ProductController;
use ProjectOnlineShop\Controllers\User\ProfileController;
use ProjectOnlineShop\Core\Config;
use ProjectOnlineShop\Core\Loggers\AppLoggerFactory;
use ProjectOnlineShop\Core\Router;
use ProjectOnlineShop\Exceptions\ConfigMissingKeyException;
use ProjectOnlineShop\Exceptions\ConfigNotFound;

date_default_timezone_set('Europe/Moscow');
const ROOT_DIR = __DIR__ . "/..";

require_once ROOT_DIR . "/vendor/autoload.php";

$logger = AppLoggerFactory::getLogger();

$router = new Router();
$router->register([
    AddController::class,
    AdminCatalogController::class,
    EditController::class,
    AuthController::class,
    CartController::class,
    UserCatalogController::class,
    HomeController::class,
    OrderController::class,
    ProductController::class,
    ProfileController::class,
]);



try {
    Config::load(ROOT_DIR);
    $logger->info("Конфиг загружен");
} catch (ConfigNotFound $e) {
    $logger->error($e->getMessage());
    ErrorController::internalError(); ///TODO Статически переделать или оставить не статическим?
    exit;
} catch (ConfigMissingKeyException $e) {
    $logger->error($e->getMessage());
    ErrorController::internalError();
    exit;
}



