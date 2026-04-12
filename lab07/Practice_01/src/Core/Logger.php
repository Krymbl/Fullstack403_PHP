<?php

namespace Practice_01\Core;

class Logger
{
    private static string $logFile = __DIR__ . "/../../runtime/logs/app.log";

    public static function error(string $message): void {
        self::write("ERROR", $message);
    }

    public static function debug(string $message): void
    {
        self::write('DEBUG', $message);
    }
    private static function write(string $level, string $message): void {
        $line = date("Y-m-d H:i:s") . " [" . $level . "] " . $message . "\n";
        file_put_contents(self::$logFile, $line, FILE_APPEND);
    }
}