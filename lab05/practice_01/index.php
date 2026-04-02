<?php

use model\Product;

require_once 'Product.php';
$database = [
    ["id" => 1, "name" => "Телефон", "price" => 10000.21],
    ["id" => 2, "name" => "Ноутбук", "price" => 49756.23],
    ["id" => 3, "name" => "Наушники", "price" => 5275.9992],
];
$products = [];

foreach ($database as $data) {
    $products[] = new Product($data["id"], $data["name"], $data["price"]);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <ul>
        <?php foreach ($products as $product) : ?>
        <li>Название: <?= htmlspecialchars($product->getTitle()) ?>
            . Цена: <?= htmlspecialchars($product->getFormattedPrice()) ?> </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
