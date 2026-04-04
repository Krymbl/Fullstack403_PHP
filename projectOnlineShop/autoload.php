<?php
spl_autoload_register(function (string $className) : void {
    $prefix = 'ProjectOnlineShop\\';
    $base_dir = __DIR__ . "/src/";

    $len = strlen($prefix);

    if (strncmp($prefix, $className, $len) !== 0) {
        return;
    }

    $relative_class = substr($className, $len);
    $file = $base_dir . str_replace("\\", "/", $relative_class) . ".php";

    if (file_exists($file)) {
        require $file;
    }
});
