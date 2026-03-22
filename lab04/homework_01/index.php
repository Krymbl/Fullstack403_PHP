<?php
session_start();
require_once "common/templates.php";

$tasks = loadTasksFromFile();

$router = [
    "GET" => [
        "/tasks" => fn() : string => renderTodoHTML(loadTasks($tasks)),
        "/editTask" => function () use ($tasks) : string {
            $id = $_REQUEST["id"];
            $_SESSION["token"] = rand(100000, 999999);
            return renderEditTaskHTML(loadTaskForm($tasks, $id));
        },
        "/createTask" => function () : string {
            $_SESSION["token"] = rand(100000, 999999);
            return renderCreateTaskHTML();
        },

    ],

    "POST" => [
        "/editTask" => fn() => formHandler("/editTask", $tasks),
        "/createTask" => fn() => formHandler("/createTask", $tasks),
        "/deleteTask" => fn() => deleteTask($tasks),
        "/deleteImage" => fn() => deleteImage($tasks),
    ],
];

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if (isset($router[$method][$path])) {
    echo $router[$method][$path]();
} else {
    http_response_code(404);
    return;
}

function deleteTask(&$tasks): void {
    $id = $_POST["id"];

    if ($_POST["token"] != $_SESSION["token"]) {
        $_SESSION["error"] = "Не совпадают токены";
        header("Location: /tasks");
        exit;
    }

    if (!isset($tasks[$id])) {
        $_SESSION["error"] = "Нет задачи с айди: " . $id;
        header("Location: /tasks");
        exit;
    }

    deleteImageFromFiles($id);
    unset($tasks[$id]);
    $_SESSION['success'] = "Задача с айди " . $id . " успешно удалена";
    saveTasksToFile($tasks);

    header("Location: /tasks");
    exit;
}

function deleteImage(&$tasks): void {
    $id = $_POST["id"];

    if (!isset($tasks[$id])) {
        $_SESSION["error"] = "Задача не найдена";
        header("Location: /tasks");
        exit;
    }

    if ($_POST["token"] != $_SESSION["token"]) {
        $_SESSION["error"] = "Не совпадают токены";
        header("Location: /tasks");
        exit;
    }

    $tasks[$id]["photo"] = "";
    deleteImageFromFiles($id);
    saveTasksToFile($tasks);
    $_SESSION['success'] = "Фото успешно удалено";
    header("Location: /editTask?id=$id");
    exit;

}

function deleteImageFromFiles($id) : void {
    if (empty($id)) return;
    $filePath = "uploads/task" . $id . ".jpg";
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

function formHandler($path, &$tasks) : void {
    $token = $_POST["token"];

    $id = $_POST["id"];
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($token != $_SESSION["token"]) {
        $_SESSION["error"] = "Не совпадают токены";
        header("Location: " . $path);
        exit;
    }


    if (empty($title) || empty($description)) {
        $_SESSION["error"] = "Не полные данные формы. Заполните полностью название и описание!";
        header("Location: " . $path);
        exit;
    }

    if (empty($id)) {
        if (empty($tasks)) {
            $id = 1;
        } else {
            $id = array_key_last($tasks) + 1;
        }

    }

    $photoPath = $tasks[$id]["photo"] ?? "";
    if (!empty($_FILES["photo"]["tmp_name"])) {
        if (!empty($photoPath) && file_exists($photoPath)) {
            unlink($photoPath);
        }
        $photoPath = "uploads/" . "task" . $id . ".jpg";
        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath)) {
            $_SESSION["error"] = "Ошибка при сохранении файла";
            header("Location: " . $path);
            exit;
        }
    }

    $task = ["title" => $title, "description" => $description, "photo" => $photoPath];
    $tasks[$id] = $task;

    if ($path === "/editTask") {
        $_SESSION["success"] = "Изменения сохранены";
    } else if ($path === "/createTask") {
        $_SESSION["success"] = "Задача создана";
    }

    unset($_SESSION["token"]);
    saveTasksToFile($tasks);
    header("Location: /tasks");
    exit;

}

function loadTasksFromFile() : array{
    if (!file_exists("database/tasks.json")) {
        return [];
    }

    $json = file_get_contents("database/tasks.json");
    return json_decode($json, true);
}

function saveTasksToFile($tasks) : void {
    $json = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents("database/tasks.json", $json);
}

function loadTaskForm($tasks, $id) : string {
    $task = $tasks[$id];
    if (empty($task)) {
        return "<p>Задача не найдена</p>";
    }
    $title = e($task["title"] ?? "Не найдено");
    $description = e($task["description"] ?? "Не найдено");
    $photo = $task["photo"];
    if (!empty($photo)) {
        $photoPlaceholder =
            <<<HTML
                <img src="$photo" alt="Фото задачи">
            HTML;
    } else {
        $photoPlaceholder = "";
    }

    return <<<HTML
    <form id="edit-form" method="post" action="/editTask" enctype="multipart/form-data">
        <input type="hidden" name="token" value="{$_SESSION["token"]}">
        <input type="hidden" name="id" value="$id">
        <input type="text" class="title" name="title" value="$title" placeholder="Название" required>
        <textarea class="description" name="description" required>$description</textarea>
        $photoPlaceholder
        <span>Изменить фото: </span>
        <input type="file" name="photo" accept="image/*">
    </form>

    <form action="/deleteImage" method="post">
        <input type="hidden" name="token" value="{$_SESSION["token"]}">
        <input type="hidden" name="id" value="$id">
        <button type="submit">Удалить фото</button>
    </form>
    
    <button type="submit" form="edit-form">Сохранить</button>
    HTML;
}

function loadTasks(array $tasks) : string {
    $result = "";
    if (empty($tasks)) {
        return $result;
    }
    foreach ($tasks as $id => $task) {
        $title = e($task["title"] ?? "Не найдено");
        $description = e($task["description"] ?? "Не найдено");
        $photo = $task["photo"];

        if (!empty($photo)) {
            $photoPlaceholder = <<<HTML
                <img src="$photo" alt="Фото задачи">
            HTML;
        } else {
            $photoPlaceholder = "";
        }

        $result .= <<<HTML
        <div class="task" id="$id">
            <h1>$title</h1>
            <p>$description</p>
            $photoPlaceholder
            <a href="/editTask?id=$id">Изменить</a>
            <form method="post" action="/deleteTask">
                <input type="hidden" name="token" value="{$_SESSION['token']}">
                <input type="hidden" name="id" value="$id">
                <button type="submit">Удалить</button>
            </form>
        </div>
        HTML;

    }
    return $result;
}


function e($str) : string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}


