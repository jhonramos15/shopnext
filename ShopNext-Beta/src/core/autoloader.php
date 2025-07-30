<?php
/**
 * Autoloader Inteligente v2
 * Este autoloader entiende que las carpetas pueden estar en minúsculas
 * pero los nombres de archivo de las clases están en PascalCase.
 */
spl_autoload_register(function ($fullClassName) {
    // ej. $fullClassName = "App\Controllers\Shop\IndexController"

    // 1. Quita el prefijo "App\" para obtener la ruta relativa de la clase
    $classPath = str_replace('App\\', '', $fullClassName); // -> "Controllers\Shop\IndexController"

    // 2. Separa el nombre del archivo del resto de la ruta del namespace
    $lastSlashPos = strrpos($classPath, '\\');
    $namespacePath = substr($classPath, 0, $lastSlashPos); // -> "Controllers\Shop"
    $className = substr($classPath, $lastSlashPos + 1);    // -> "IndexController"

    // 3. Convierte la RUTA del namespace a minúsculas y con barras correctas
    $directoryPath = strtolower(str_replace('\\', '/', $namespacePath)); // -> "controllers/shop"

    // 4. Construye la ruta final al archivo, respetando las mayúsculas del nombre del archivo
    // La ruta será: .../src/controllers/shop/IndexController.php
    $file = __DIR__ . '/../../src/' . $directoryPath . '/' . $className . '.php';

    // 5. Si el archivo existe, lo carga.
    if (file_exists($file)) {
        require_once $file;
    }
});
?>