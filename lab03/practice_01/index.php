<?php
$array = [ "/index" => fn() : string => "Вы на главной странице",
    "/home" => fn() : string => "Вы на домашней странице",
    "/about" => fn() : string => "Вы на справочной странице",
    "/contact" => fn() : string => "Вы на контактной странице" ];

$uri = $_SERVER["REQUEST_URI"];
$path = parse_url($uri, PHP_URL_PATH);

switch ($path) {
    case "/home":
    case "/index":
    case "/about":
    case "/contact":
        echo $array[$path]();
        break;
    default:
        http_response_code(404);
}