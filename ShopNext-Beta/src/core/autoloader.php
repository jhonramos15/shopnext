<?php
/**
 * Autoloader Definitivo PSR-4.
 * Mapea los namespaces a las carpetas correctas.
 */
spl_autoload_register(function ($class) {
    // Mapeo de prefijos de namespace a directorios base.
    $prefixes = [
        "App\\"    => __DIR__ . '/../',         // El namespace App\ se busca en la carpeta src/
        "Config\\" => __DIR__ . '/../../config/', // El namespace Config\ se busca en la carpeta config/
    ];

    foreach ($prefixes as $prefix => $base_dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Incluimos el autoloader de Composer si existe, para que PHPMailer funcione.
$composer_autoloader = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($composer_autoloader)) {
    require_once $composer_autoloader;
}