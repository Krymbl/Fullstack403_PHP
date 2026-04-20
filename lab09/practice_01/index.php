<?php
require_once "Container.php";
require_once "UserService.php";
require_once "Logger.php";
require_once "Mailer.php";

$container = new Container();
$userService = $container->get(UserService::class);
$userService->register('user@example.com');