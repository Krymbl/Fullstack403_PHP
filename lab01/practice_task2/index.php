<?php
date_default_timezone_set('Europe/Moscow');
$name = isset($_GET["name"]) ? htmlspecialchars($_GET["name"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"): null ;
$greeting = $name ? "Привет, $name!" : "Добро пожаловать!";

$date = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Приветствие</title>
        <meta charset="utf-8">
    </head>
    <body>
        <h1><?php echo $greeting; ?></h1>
        <p><strong>Текущая дата/время:</strong> <?php echo $date; ?></p>

        <h2>Server Data</h2>
        <ul>
            <li><strong>PHP_SELF:</strong> <?php echo $_SERVER['PHP_SELF']; ?></li>
            <li><strong>SERVER_NAME:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></li>
            <li><strong>REQUEST_METHOD:</strong> <?php echo $_SERVER['REQUEST_METHOD']; ?></li>
            <li><strong>REMOTE_ADDR:</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></li>
        </ul>

    </body>
</html>