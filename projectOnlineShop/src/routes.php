<?php
use projectOnlineShop\Controllers\Admin\AddController;
use projectOnlineShop\Controllers\Admin\CatalogController as AdminCatalogController;
use projectOnlineShop\Controllers\Admin\EditController;
use ProjectOnlineShop\Controllers\user\AuthController;
use ProjectOnlineShop\Controllers\User\CartController;
use ProjectOnlineShop\Controllers\User\CatalogController as UserCatalogController;
use ProjectOnlineShop\Controllers\User\HomeController;
use ProjectOnlineShop\Controllers\User\OrderController;
use ProjectOnlineShop\Controllers\User\ProductController;
use ProjectOnlineShop\Controllers\User\ProfileController;

$routes = [
    "GET" => [
        "/admin/add" => AddController::class,
        "/admin/catalog" => AdminCatalogController::class,
        "/admin/edit/{id}" => EditController::class,
        "/" => HomeController::class,
        "/login" => AuthController::class,
        "/registration" => AuthController::class,
        "/cart" => CartController::class,
        "/catalog" => UserCatalogController::class,
        "/order" => OrderController::class,
        "/order/success" => OrderController::class,
        "/order/error" => OrderController::class,
        "/product/{id}" => ProductController::class,
        "/profile" => ProfileController::class,
        "/profile/orders" => ProfileController::class,
        "/profile/orders/{id}" => ProfileController::class,
    ],
    "POST" => [
        "/admin/add" => AddController::class,
        "/admin/edit/{id}" => EditController::class,
        "/login" => AuthController::class,
        "/registration" => AuthController::class,
        "/logout" => AuthController::class,
        "/cart" => CartController::class,
        "/order" => OrderController::class,
    ]
];