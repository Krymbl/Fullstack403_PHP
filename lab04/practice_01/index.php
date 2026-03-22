<?php
session_start();

$router = [
        "GET" => [
                "/form" => fn() : string => getHtmlGetForm(),
            ],
        "POST" => [
                "/index" => fn() : string => formHandler()
        ]
];

$uri = $_SERVER["REQUEST_URI"];
$path = parse_url($uri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

foreach ($router[$method] ?? [] as $routePath => $handler) {
    if ($routePath === $path) {
        echo $handler();
        return;
    }
}
http_response_code(404);


function formHandler() {
    $token = $_SESSION["token"] ?? null;

    $userLogin = trim($_POST["login"] ?? '');
    $userPassword = trim($_POST["password"] ?? '');
    $username = trim($_POST["username"] ?? '');
    $userSurname = trim($_POST["username"] ?? '');
    $userToken = $_POST["token"];

    if ($userToken != $token) {
        $_SESSION['error'] = "Не совпадают токены";
        header("Location: /form");
        exit;
    }
    if (empty($userLogin) || strlen($userLogin) < 4) {
        $_SESSION['error'] = "Логин должен быть больше 3 символов";
        header("Location: /form");
        exit;
    }
    if (empty($userPassword) || strlen($userPassword) < 4) {
        $_SESSION['error'] = "Пароль должен быть больше 3 символов";
        header("Location: /form");
        exit;
    }
    if (empty($username)) {
        $_SESSION['error'] = "Напишите имя";
        header("Location: /form");
        exit;
    }
}

function getHtmlGetForm() : string {
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = rand(100000, 999999);
    }
        $htmlGetForm = <<<HTML
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
HTML;
if ($_SESSION["error"]) {
    $htmlGetForm .= '<div class="error_information">';
    $htmlGetForm .= '<span style="color:red">' . htmlspecialchars($_SESSION["error"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") . '</span>';
    $htmlGetForm .= '</div>';
};
$htmlGetForm .= <<<HTML
    <div class="main">
        <form action="/index" method="post">
            <input type="hidden" name="token" value="{$_SESSION["token"]}">
            <input type="text" name="login" placeholder="Ваш логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="text" name="username" placeholder="Ваше имя" required>
            <input type="text" name="surname" placeholder="Ваша фамилия">
            <button type="submit">Отправить</button>
        </form>
    </div>
</body>
</html>
HTML;
    return $htmlGetForm;
}







