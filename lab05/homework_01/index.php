<?php
use model\Cart;

require_once "model/Product.php";
require_once "model/Cart.php";
require_once "common/templates.php";
require_once "database/products.php";
session_start();


$router = [
    "GET" => [
        "/index" => fn() : string => indexGetHandler(),
        "/cart" => fn() : string => cartGetHandler(),
        "/remove" => fn() => removeGetHandler(),
        "/clear" => fn() => clearGetHandler(),
        "/add" => fn() => addGetHandler(),
    ],
];
$uri = $_SERVER["REQUEST_URI"];
$path = parse_url($uri, PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

if (isset($router[$method][$path])) {
    echo $router[$method][$path]();
} else {
    http_response_code(404);
    return;
}

function indexGetHandler() : string {
    $_SESSION["products"] = getProducts();
    $products = $_SESSION['products'];
    return getIndexHtml($products);
}

function addGetHandler() : void {
    $id = $_GET["id"];

    if (empty($id)) {
        $_SESSION["error"] = "Нет айди в запросе, добавить не получилось";
        header("Location: /index");
        exit;
    }

    $products = $_SESSION['products'];

    $product = $products[$id];


    if (empty($product)) {
        $_SESSION["error"] = "Нет такого продукта, добавить не получилось";
        header("Location: /index");
        exit;
    }

    $cart = $_SESSION["cart"];
    if (!isset($cart) || !($cart instanceof Cart)) {
        $cart = new Cart();
        $_SESSION["cart"] = $cart;
    }

    $cart->add($product);

    $_SESSION["success"] = "Добавили в корзину товар: " . $product->getTitle();
    header("Location: /index");
}

function cartGetHandler() : string {
    $cart = $_SESSION["cart"];
    if (!isset($cart)) {
        $cart = new Cart();
        $_SESSION["cart"] = $cart;
    }
    return getCartHtml($cart);

}

function removeGetHandler() : void {
    $id = $_GET["id"];
    $cart = $_SESSION["cart"];

    if (empty($id)) {
        $_SESSION["error"] = "Нет айди, удалить позицию не получилось " . $id . " 123123123";
        header("Location: /cart");
        exit;
    }

    if (empty($cart) || empty($cart->getItems())) {
        $_SESSION["error"] = "Корзина пустая, удалить позицию не получилось";
        header("Location: /cart");
        exit;
    }

    $items = $cart->getItems();
    if (empty($items[$id])) {
        $_SESSION["error"] = "Нет такого товара в корзине, удалить позицию не получилось";
        header("Location: /cart");
        exit;
    }
    $productTitle = $items[$id]['product']->getTitle();

    $cart->remove($id);

    $_SESSION["success"] = "Удалили позицию с корзины: " . $productTitle;
    $_SESSION["cart"] = $cart;
    header("Location: /cart");

}

function clearGetHandler() : void {
    $cart = $_SESSION["cart"];

    if (!isset($cart)) {
        $_SESSION["cart"] = new Cart();
    } else {
        $cart->clear();
    }
    $_SESSION["success"] = "Корзина очищена";
    header("Location: /cart");
}
