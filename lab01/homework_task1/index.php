<?php
$name = isset($_GET["name"]) ? htmlspecialchars(trim($_GET["name"]), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : "гость";
$role = isset($_GET["role"]) ? htmlspecialchars(trim($_GET["role"]), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : "guest";

$protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ? "https://" : "http://";

$uri = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$greeting = "Добрый день";
if ($role === "admin") {
    $greeting .= ", админ $name";
} else {
    $greeting .= ", $name";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Приветствие домашкка</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1><?php echo $greeting; ?></h1>
        <ul>
            <li><strong>Метод:</strong> <?php echo $_SERVER["REQUEST_METHOD"]; ?></li>
            <li><strong>Полный URI: </strong> <?php echo htmlspecialchars($uri); ?></li>
        </ul>
    </body>
</html>
