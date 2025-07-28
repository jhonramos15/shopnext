<?php
/**
 * init.php - Archivo de Inicialización Global
 *
 * Carga todas las configuraciones, clases y funciones necesarias
 * para que la aplicación funcione correctamente.
 */

// 1. Definir Constantes Globales
// Define la ruta raíz del proyecto para facilitar la inclusión de archivos.
define('ROOT_PATH', dirname(__DIR__, 2)); // Sube dos niveles desde /src/core/

// 2. Cargar la Configuración de la Base de Datos
// Al incluir este archivo, la clase 'Database' ya está disponible en toda la app.
require_once ROOT_PATH . '/config/database.php';

// 3. Cargar Funciones de Ayuda (Helpers)
// Funciones que usas a menudo, como formatear precios, sanitizar datos, etc.
// require_once ROOT_PATH . '/src/core/helpers.php'; 

// 4. Configurar el Autoloader (Opcional, pero muy recomendado)
// En lugar de hacer 'require_once' para cada modelo o controlador,
// un autoloader los carga automáticamente cuando los necesitas.
/*
spl_autoload_register(function ($className) {
    $file = ROOT_PATH . '/src/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
*/

// 5. Configurar el Manejo de Errores
// Para mostrar páginas de error bonitas en lugar de errores de PHP en producción.
// error_reporting(0);
// set_exception_handler('myExceptionHandler');

// ¡El ambiente está listo! La aplicación ya puede funcionar