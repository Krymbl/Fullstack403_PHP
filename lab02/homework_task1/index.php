<?php
$products = getProducts();
mb_internal_encoding('UTF-8');

$q = e(trim($_GET["q"] ?? ""));

$maxPrice = $_GET["max"] ?? INF;
if (!is_numeric($maxPrice)) {
    $maxPrice = INF;
}

$minPrice = $_GET["min"] ??  0;
if (!is_numeric($minPrice)) {
    $minPrice = 0;
}

$kindSort = "id";
if (isset($_GET["sort"])) {
    $sort = mb_strtolower($_GET["sort"]);
    if ($sort === "price" || $sort === "name") {
        $kindSort = $sort;
    }
}

$dirSort = "asc";
if (isset($_GET["dir"])) {
    $dir = mb_strtolower($_GET["dir"]);
    if ($dir === "desc") {
        $dirSort = $dir;
    }
}

$perPage = $_GET["perPage"] ?? count($products);
if (!is_numeric($perPage) || $perPage < 1) {
    $perPage = count($products);
}

$totalPages = ceil(count($products) / $perPage);

$numberPage = $_GET["page"] ?? 1;
if (is_numeric($numberPage)) {
    if ($numberPage < 1) {
        $numberPage = 1;
    } else if ($numberPage > $totalPages) {
        $numberPage = $totalPages;
    }
} else {
    $numberPage = 1;
}

$offset = ($numberPage - 1) * $perPage;


if ($dirSort === "asc") {
    if ($kindSort === "price") {
        usort($products, fn($a, $b) => $a["price"] - $b["price"]);
    } else if ($kindSort === "name") {
        usort($products, fn($a, $b) => strcmp($a["name"], $b["name"]));
    } else {
        usort($products, fn($a, $b) => $a["id"] - $b["id"]);
    }

} else if ($dirSort === "desc") {
    if ($kindSort === "price") {
        usort($products, fn($a, $b) => $b["price"] - $a["price"]);
    } else if ($kindSort === "name") {
        usort($products, fn($a, $b) => strcmp($b["name"], $a["name"]));
    } else {
        usort($products, fn($a, $b) => $b["id"] - $a["id"]);
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Домашка 2, каталог товаров</title>
    <meta charset="UTF-8">
</head>
<body>
    <div>
        <h2>Страницы</h2>
        <?php for($i = 1; $i <= $totalPages; $i++) : ?>
        <a href="?<?= http_build_query(array_merge($_GET, ["page" => $i])) ?>"
           style="<?= $i == $numberPage ? "font-weight:bold; color:blue" : "color:black"; ?>">
        <?= $i ?>
        </a>
        <?php endfor; ?>
    </div>
    <div>
        <h1>Найденные товары</h1>
        <ul>
            <?php foreach (array_slice($products, $offset, $perPage) as $product) : ?>
            <?php if ((mb_stripos($product["name"] . implode(" ", $product["tags"]), $q) !== false)
                        && ($product["price"] >= $minPrice) && ($product["price"] <= $maxPrice) ) : ?>
            <li>
                <?="Название: " . e($product["name"])?> <br>
                <?="ID: " . e($product["id"])?> <br>
                <?="Цена: " . e($product["price"])?> <br>
            </li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>



<?php

function e(string $string) : string {
    return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
function getProducts(): array {
    return [
            [
                    'id' => 1,
                    'name' => 'Ноутбук ASUS ROG Strix',
                    'price' => 89990,
                    'tags' => ['ноутбуки', 'игровые', 'asus', 'windows']
            ],
            [
                    'id' => 2,
                    'name' => 'Смартфон Xiaomi 13 Pro',
                    'price' => 54990,
                    'tags' => ['смартфоны', 'xiaomi', 'android', 'камера']
            ],
            [
                    'id' => 3,
                    'name' => 'Наушники Sony WH-1000XM4',
                    'price' => 24990,
                    'tags' => ['наушники', 'sony', 'беспроводные', 'шумоподавление']
            ],
            [
                    'id' => 4,
                    'name' => 'Планшет Samsung Tab S8',
                    'price' => 45990,
                    'tags' => ['планшеты', 'samsung', 'android', 'электроника']
            ],
            [
                    'id' => 5,
                    'name' => 'Монитор LG 27" 4K',
                    'price' => 32990,
                    'tags' => ['мониторы', 'lg', '4k', 'компьютеры']
            ],
            [
                    'id' => 6,
                    'name' => 'Клавиатура Logitech MX Keys',
                    'price' => 8990,
                    'tags' => ['клавиатуры', 'logitech', 'беспроводные', 'аксессуары']
            ],
            [
                    'id' => 7,
                    'name' => 'Мышка Razer DeathAdder',
                    'price' => 3990,
                    'tags' => ['мышки', 'razer', 'игровые', 'аксессуары']
            ],
            [
                    'id' => 8,
                    'name' => 'Внешний диск Samsung T7 1TB',
                    'price' => 8990,
                    'tags' => ['хранение', 'ssd', 'samsung', 'аксессуары']
            ],
            [
                    'id' => 9,
                    'name' => 'Роутер TP-Link Archer AX50',
                    'price' => 5990,
                    'tags' => ['сетевое', 'роутеры', 'wi-fi', 'tplink']
            ],
            [
                    'id' => 10,
                    'name' => 'Веб-камера Logitech C920',
                    'price' => 6990,
                    'tags' => ['веб-камеры', 'logitech', 'стриминг', 'аксессуары']
            ],
            [
                    'id' => 11,
                    'name' => 'Микрофон Blue Yeti',
                    'price' => 12990,
                    'tags' => ['микрофоны', 'аудио', 'стриминг', 'запись']
            ],
            [
                    'id' => 12,
                    'name' => 'Принтер HP LaserJet',
                    'price' => 15990,
                    'tags' => ['принтеры', 'hp', 'лазерные', 'офис']
            ],
            [
                    'id' => 13,
                    'name' => 'Игровая консоль Sony PS5',
                    'price' => 49990,
                    'tags' => ['игровые', 'sony', 'приставки', 'развлечения']
            ],
            [
                    'id' => 14,
                    'name' => 'Смарт-часы Samsung Watch 5',
                    'price' => 19990,
                    'tags' => ['часы', 'samsung', 'носимые', 'android']
            ],
            [
                    'id' => 15,
                    'name' => 'Зарядное устройство Anker 65W',
                    'price' => 2490,
                    'tags' => ['зарядки', 'anker', 'аксессуары', 'powerbank']
            ],
            [
                    'id' => 16,
                    'name' => 'Колонка JBL Charge 5',
                    'price' => 9990,
                    'tags' => ['колонки', 'jbl', 'bluetooth', 'аудио']
            ],
            [
                    'id' => 17,
                    'name' => 'Графический планшет Wacom One',
                    'price' => 8990,
                    'tags' => ['планшеты', 'wacom', 'дизайн', 'творчество']
            ],
            [
                    'id' => 18,
                    'name' => 'VR-очки Oculus Quest 2',
                    'price' => 29990,
                    'tags' => ['vr', 'игровые', 'oculus', 'развлечения']
            ],
            [
                    'id' => 19,
                    'name' => 'Умная лампа Xiaomi',
                    'price' => 1490,
                    'tags' => ['умный дом', 'xiaomi', 'освещение', 'аксессуары']
            ],
            [
                    'id' => 20,
                    'name' => 'Кабель Apple USB-C',
                    'price' => 1490,
                    'tags' => ['кабели', 'apple', 'аксессуары', 'зарядки']
            ]
    ];
}


?>