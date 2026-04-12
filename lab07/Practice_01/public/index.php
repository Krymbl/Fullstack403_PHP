<?php

use Practice_01\Core\Config;
use Practice_01\Core\Logger;
use Practice_01\Exceptions\ConfigMissingKeyException;
use Practice_01\Exceptions\ConfigNotFound;

require_once __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('Europe/Moscow');

const ROOT_DIR = __DIR__ . "/..";

try {
    Config::load(ROOT_DIR);
    Logger::debug("Конфиг загружен");
    Logger::debug('APP_NAME: ' . ($_ENV["APP_NAME"] ?? null) . "; " .
        'APP_ENV: ' . ($_ENV["APP_ENV"] ?? null) . "; " .
        'APP_DEBUG: ' . ($_ENV["APP_DEBUG"] ?? null) . "; " .
        'DB_HOST: ' . ($_ENV["DB_HOST"] ?? null) . "; " .
        'DB_NAME: ' . ($_ENV["DB_NAME"] ?? null) . "; " .
        'DB_USER: ' . ($_ENV["DB_USER"] ?? null) . "; " .
        'DB_PASS: ' . ($_ENV["DB_PASS"] ?? null));
} catch (ConfigNotFound $e) {
    Logger::error($e->getMessage());
} catch (ConfigMissingKeyException $e) {
    Logger::error($e->getMessage());
} catch (\Exception $e) {
    Logger::error($e->getMessage());
}