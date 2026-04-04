<?php

use ProjectOnlineShop\Controllers\Admin\AddController;
use ProjectOnlineShop\Controllers\Admin\CatalogController as AdminCatalogController;
use ProjectOnlineShop\Controllers\Admin\EditController;
use ProjectOnlineShop\Controllers\User\AuthController;
use ProjectOnlineShop\Controllers\User\CartController;
use ProjectOnlineShop\Controllers\User\CatalogController as UserCatalogController;
use ProjectOnlineShop\Controllers\User\HomeController;
use ProjectOnlineShop\Controllers\User\OrderController;
use ProjectOnlineShop\Controllers\User\ProductController;
use ProjectOnlineShop\Controllers\User\ProfileController;
use ProjectOnlineShop\Core\Router;

$routes = [
    "GET" => [
        "/admin/add" => ["controller" => AddController::class, "action" => ""],
        "/admin/catalog" => ["controller" => AdminCatalogController::class, "action" => ""],
        "/admin/edit/{id}" => ["controller" => EditController::class, "action" => ""],
        "/" => ["controller" => HomeController::class, "action" => ""],
        "/login" => ["controller" => AuthController::class, "action" => ""],
        "/registration" => ["controller" => AuthController::class, "action" => ""],
        "/cart" => ["controller" => CartController::class, "action" => ""],
        "/catalog" => ["controller" => UserCatalogController::class, "action" => ""],
        "/order" => ["controller" => OrderController::class, "action" => ""],
        "/order/success" => ["controller" => OrderController::class, "action" => ""],
        "/order/error" => ["controller" => OrderController::class, "action" => ""],
        "/product/{id}" => ["controller" => ProductController::class, "action" => ""],
        "/profile" => ["controller" => ProfileController::class, "action" => ""],
        "/profile/orders" => ["controller" => ProfileController::class, "action" => ""],
        "/profile/orders/{id}" => ["controller" => ProfileController::class, "action" => ""],
    ],
    "POST" => [
        "/admin/add" => ["controller" => AddController::class, "action" => ""],
        "/admin/edit/{id}" => ["controller" => EditController::class, "action" => ""],
        "/login" => ["controller" => AuthController::class, "action" => ""],
        "/registration" => ["controller" => AuthController::class, "action" => ""],
        "/logout" => ["controller" => AuthController::class, "action" => ""],
        "/cart" => ["controller" => CartController::class, "action" => ""],
        "/order" => ["controller" => OrderController::class, "action" => ""],
    ]
];

$router = new Router();
$router->addRoutes($routes);

return $router;

