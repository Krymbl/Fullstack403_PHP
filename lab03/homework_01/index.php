<?php
$routes = [
    "GET" => [
        "/index" => fn() : string =>
        '<div class="main">
            <h1> Вы на главной странице </h1>
        </div>
        <div class="nav">
            <a href="/form">На форму</a>
        </div>',

        "/form" => fn() : string =>
        '<div class="main">
            <h1> Вы на странице с формой </h1>
            <h2>Заполните форму</h2>
        </div>
        <div class="form">
            <form action="/form" method="post">
                <input type="text" name="name" placeholder="Ваше имя">
                <input type="text" name="surname" placeholder="Ваша фамилия">
                <button type="submit">Отправить</button>
            </form>
        </div>
        <div class="nav">
            <a href="/index">На главную</a>
        </div>'

    ],

    "POST" => [
        "/form" => function() : string {
            $name = e(trim($_POST["name"] ?? "") ?: "Аноним");
            $surname = e(trim($_POST["surname"] ?? "") ?: "Анонимов");

            return "<div class='hello'> 
                        <h1>Привет $name $surname</h1>                    
                    </div>
                    <div class='nav'>
                        <a href='/index'>На главную</a>
                        <a href='/form'>На форму</a>
                    </div>";
        }
    ],

];

$uri = $_SERVER["REQUEST_URI"];
$path = parse_url($uri, PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

foreach ($routes[$method] ?? [] as $routePath => $handler) {
    if ($routePath === $path) {
        echo $handler();
        return;
    }
}

http_response_code(404);
echo "<div class='error'>
        <h1>404 Not Found</h1>
      </div>
      <div class='nav'>
        <a href='/index'>На главную</a>
        </div>";
return;


function e($str) : string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

