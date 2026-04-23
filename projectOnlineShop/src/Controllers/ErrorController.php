<?php

namespace ProjectOnlineShop\Controllers;

use ProjectOnlineShop\Core\AbstractController;

class ErrorController extends AbstractController {

    public static function internalError() : void {
        http_response_code(500);

        echo "<html lang='ru'>
                <head>
                    <meta charset='utf-8'>
                    <title>500 - Ошибка сервера</title>
                </head>
                <body>
                    <h1>500 - Ошибка сервера</h1>
                    <p>Что-то пошло не так. Попробуйте позже</p>
                </body>
                </html>
            ";
    }

    public static function notFound() : void {
        http_response_code(404);
        echo "<html lang='ru'>
                <head>
                    <meta charset='utf-8'>
                    <title>404 - Страница не найдена</title>
                </head>
                <body>
                    <h1>404 - Страница не найдена</h1>
                    <p>Запрашиваемой страницы не существует</p>
                </body>
                </html>
            ";
    }

    public static function accessDenied() : void {
        http_response_code(403);
        echo "<html lang='ru'>
                <head>
                    <meta charset='utf-8'>
                    <title>403 - Доступ запрещен</title>
                </head>
                <body>
                    <h1>403 - Доступ запрещен</h1>
                    <p>У вас нет прав для просмотра этой страницы</p>
                </body>
                </html>
            ";
    }

}