<?php
use model\Product;

require_once __DIR__ . "/../model/Product.php";

function getProducts() : array {
    return [
        1 => new Product(1, 'Ноутбук Apple MacBook Air 13"', 89999.99),
        2 => new Product(2, 'Смартфон Samsung Galaxy S24', 74999.00),
        3 => new Product(3, 'Наушники Sony WH-1000XM5', 29990.00),
        4 => new Product(4, 'Планшет iPad Air 10.9"', 58999.00),
        5 => new Product(5, 'Умные часы Apple Watch Series 9', 39999.00),
        6 =>new Product(6, 'Фотоаппарат Canon EOS 2000D', 42990.00),
        7 =>new Product(7, 'Монитор LG UltraGear 27"', 25999.00),
        8 =>new Product(8, 'Клавиатура Logitech MX Keys', 11990.00),
        9 =>new Product(9, 'Мышь Logitech MX Master 3S', 9990.00),
        10 =>new Product(10, 'Внешний SSD Samsung T7 1TB', 12990.00),
    ];
}
