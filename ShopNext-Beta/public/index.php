<?php
/**
 * ShopNext - Punto de Entrada Único (Router Principal)
 */

// --- Manejo de CORS para la API ---
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); // Cache por 1 día
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    }
    exit(0);
}

// 1. Carga la configuración inicial y el autoloader mágico
require_once __DIR__ . '/../src/core/init.php';

// Importamos TODOS los controladores que vamos a usar en este archivo.
// Es como poner las herramientas sobre la mesa antes de trabajar.
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegistroController;
use App\Controllers\Shop\HomeController;


// 3. Obtenemos la página solicitada de la URL
$page = $_GET['action'] ?? 'home';


// 4. El router ahora sabe qué es cada controlador gracias al 'use'
switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->home();
        break;

    case 'login':
        $controller = new LoginController();
        // Decide si mostrar el formulario o procesar los datos
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handleLogin();
        } else {
            $controller->showLoginForm();
        }
        break;

    case 'signup':  // Muestra el formulario de registro
        $filePath = __DIR__ . '/../views/auth/sign-up.html';
        
        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            // Esto te ayudará a depurar si la ruta sigue mal en el futuro
            http_response_code(500);
            echo "Error del servidor: No se encuentra el archivo de la vista de registro.";
            error_log("Ruta no encontrada: " . $filePath);
        }
        break;

    case 'register': // Para PROCESAR los datos del formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new RegistroController();
            // ✅ CORRECCIÓN 3: Llamamos al método correcto 'handleRegistration'.
            $controller->handleRegistration();
        } else {
            // No se permite acceder a esta ruta por GET
            header('Location: index.php?action=signup');
            exit;
        }
        break;
    case 'logout':
        $controller = new LoginController();
        $controller->logout();
        break;

    // ... (otros casos para 'products', 'cart', etc.)

default:
    // Establecemos el código de respuesta HTTP correcto para "No Encontrado"
    http_response_code(404);
    
    // Cargamos nuestra página HTML personalizada para el error 404
    require __DIR__ . '/../views/error/404.html';
    break;
}
?>