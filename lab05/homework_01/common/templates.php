<?php

use model\Cart;

function getIndexHtml(array $products) : string {
    $placeholder = "";

    foreach ($products as $product) {
        $productId = e($product->getId());
        $productTitle = e($product->getTitle());
        $productPrice = e($product->getPrice());
        $placeholder .= <<<HTML
            <div class="product">
                <input type="hidden" name="id" value="$productId">
                <label>Название: $productTitle</label>
                <span>Цена: $productPrice</span>
                <form action="/add" method="get">
                    <input type="hidden" name="id" value="$productId">
                    <button type="submit">Добавить в корзину</button>
                </form>
            </div>
        HTML;

    }

    $informationHtml = getInformationHTML();
    return <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Онлайн-магазин</title>
        <style>
            .main {
                 font-size: 20px;
                 padding: 10px;
            }
            .success {
                width: fit-content;
                background-color: azure;
                color: green;
                padding: 10px;
                border-radius: 10px;
                margin: 10px 5px;
            }
            .error {
                width: fit-content;
                background-color: lightpink;
                color: red;
                padding: 10px;
                border-radius: 10px;
                margin: 10px 5px;
            }
            .product {
                background-color: wheat;
                padding: 10px;
                border-radius: 10px;
                margin: 10px 5px;
                display: flex;
                flex-direction: column;
            }
        </style>
    </head>
    <body>
        <div class="main">
            {$informationHtml}
            <div class="products">
                <h1>Список товаров: </h1>
                $placeholder
            </div>
            <div class="navigation">
                <a href="/cart">Перейти в корзину</a>
            </div>
        </div>
    </body>
    </html>
    HTML;

}

function getCartHtml(Cart $cart) : string {
    $placeholder = "";
    $cartProducts = $cart->getItems();

    foreach ($cartProducts as $id => $item) {
        $product = $item['product'];
        $quantity = $item['quantity'];

        $productId = e($product->getId());
        $productTitle = e($product->getTitle());
        $productPrice = e($product->getPrice());

        $placeholder .= <<<HTML
            <div class="cart-product">
                <input type="hidden" name="id" value=$productId>
                <label>Название: $productTitle</label>
                <span>Кол-во: $quantity</span>
                <span>Цена: $productPrice</span> 
                <form action="/remove" method="get">
                    <input type="hidden" value="$productId" name="id">
                    <button type="submit">Удалить</button>
                </form> 
            </div>
        HTML;
    }

    $informationHtml = getInformationHTML();
    return <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Корзина</title>
        <style>
        .main {
             font-size: 20px;
             padding: 10px;
            }
        .cart-product {
            background-color: wheat;
            padding: 10px;
            border-radius: 10px;
            margin: 10px 5px;
            display: flex;
            flex-direction: column;
        }
        .success {
            width: fit-content;
            background-color: azure;
            color: green;
            padding: 10px;
            border-radius: 10px;
            margin: 10px 5px;
        }
        .error {
            width: fit-content;
            background-color: lightpink;
            color: red;
            padding: 10px;
            border-radius: 10px;
            margin: 10px 5px;
        }
        </style>
    </head>
    <body>
        <div class="main">
        {$informationHtml}
            <div class="cart">
            <h1>Корзина: </h1>
                $placeholder
                <span>Общая стоимость: {$cart->getTotal()}</span>
                <form action="/clear" method="get">
                    <button type="submit">Очистить корзину</button>
                </form>
            </div>
            
            <div class="navigation">
               <a href="/index">Вернуться к покупкам</a>
            </div>
        </div>
    </body>
    </html>
    HTML;

}

function getInformationHTML(): string {
    $error = $_SESSION["error"];
    $success = $_SESSION["success"];

    $informationHTML = "";

    if (!empty($error)) {
        $informationHTML = <<<HTML
        <div class="error">
                {$error}
        </div>
        HTML;
    } else if (!empty($success)) {
        $informationHTML = <<<HTML
        <div class="success">
            {$success}
        </div>
        HTML;

    }

    unset($_SESSION["error"]);
    unset($_SESSION["success"]);

    return $informationHTML;
}

function e($string) : string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');

}
