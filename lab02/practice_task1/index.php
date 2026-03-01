<?php

$name = isset($_GET["name"]) ? htmlspecialchars(trim($_GET["name"]), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : "гость";
$role = isset($_GET["role"]) ? htmlspecialchars(trim($_GET["role"]), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") : "guest";
$skills = isset($_GET["skills"]) ?? [];

if (!is_array($skills)) {
    $skills = explode(",", $skills);
}

foreach ($skills as &$skill) {
    $skill = htmlspecialchars(trim($skill), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
$profile = [
    "name" => $name,
    "role" => $role,
    "skills" => $skills,
];

print_r ($profile);


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Практика</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1>Имя: <?= $name ?>, Роль: <?= $role ?></h1>
        <h2>Навыки:</h2>
        <?php if (!empty($profile["skills"])) : ?>
            <ul>
                <?php foreach ($profile["skills"] as $skill) : ?>
                    <li><?= $skill ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Навыки не указаны</p>
        <?php endif; ?>
    </body>
</html>

