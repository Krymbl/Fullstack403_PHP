<?php
function renderCreateTaskHTML() : string {
    $informationHTML = getInformationHTML();

    return <<<HTML
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Добавление задачи</title>
        <style>
            .main {
                background-color: wheat;
                padding: 20px;
                margin: 20px 50px;
                border-radius: 10px;
                min-height: 100vh  
            }
            .form form {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .form button {
                width: max-content;
            }
            .title{
                width: 20%;
                height: 30px;
                padding: 2px 10px;
                font-weight:bold
            }
            .description{
                width: 50%;
                height: 50px;
                padding: 10px;
                resize: none;
            }
            
        </style>
    </head>
    <body>
        <div class="main">
            {$informationHTML}
            <div class="form">
                <form method="post" action="/createTask" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="{$_SESSION["token"]}">
                    <input type="text" class="title" name="title" placeholder="Название" style="" required>
                    <textarea class="description" name="description" required></textarea>
                    <span> Фото: <input type="file" name="photo" accept="image/*"> </span>
                    <button type="submit">Сохранить</button>
                </form>
            </div>
            <div class="navigation">
                <a href="/tasks">На главную</a>
            </div>
        </div>
    </body>
    </html>
    HTML;

}

function renderEditTaskHTML($placeholderHTML) : string {
    $informationHTML = getInformationHTML();

    return <<<HTML
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Изменение задачи</title>
        <style>
        .main {
            background-color: wheat;
            padding: 20px;
            margin: 20px 50px;
            border-radius: 10px;
            min-height: 100vh  
        }
        .to-do_task form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin: 0 auto;
            align-items: flex-start;
        }
        .to-do_task button {
            width: max-content;
        }
        .title{
            font-weight:bold;
            width: 20%;
            height: 30px;
            padding: 2px 10px;
        }
        .description{
            width: 50%;
            height: 50px;
            padding: 10px;
            resize: none;
        }
        .to-do_task img {
            max-height: 400px;
            max-width: 100%;
            width: auto;
            height: auto;
            display: block;
            object-fit: contain;
        }
        </style>
    </head>
    <body>
        <div class="main">
            {$informationHTML}
            <div class="to-do_task">
                $placeholderHTML
            </div>
            <div class="navigation">
                <a href="/tasks">На главную</a>
            </div>
        </div>
    </body>
    </html>
    HTML;
}

function renderTodoHTML($placeholderHTML) : string {
    $informationHTML = getInformationHTML();

    return <<<HTML
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Список задач</title>
        <style>
        .main {
            background-color: wheat;
            padding: 20px;
            margin: 20px 50px;
            border-radius: 10px;
            min-height: 100vh
        }
        .task {
            margin: 10px 0;
            border: 2px solid black;
            padding: 10px;
            background-color: white;
            border-radius: 10px;
        }
        .task p {
            border: 1px solid black;
            padding: 5px;
        }
        .task img {
            max-height: 200px;
            max-width: 100%;
            width: auto;  
            display: block;
        }
        
</style>
    </head>
    <body>
        <div class="main">
            {$informationHTML}
            <div class="tools">
                <a href="/createTask">Создать задачу</a>
            </div>
            <div class="to-do_tasks">
                $placeholderHTML
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
        <div class="error" style="color: red;">
                {$error}
        </div>
        HTML;
    } else if (!empty($success)) {
        $informationHTML = <<<HTML
        <div class="success" style="color: green;">
            {$success}
        </div>
        HTML;

    }

    unset($_SESSION["error"]);
    unset($_SESSION["success"]);

    return $informationHTML;
}
