<?php
// 1. Iniciar la sesión para poder usar variables como $_SESSION['user'] en toda la app.
session_start();

// 2. Cargar archivos esenciales
// Este 'init.php' podría contener la conexión a la BD, funciones de ayuda, etc.
require_once __DIR__ . '/../src/core/init.php'; 

// 3. Sistema de Enrutamiento Básico
// Si no se especifica nada, la página por defecto será 'home'.
$page = $_GET['page'] ?? 'home';

// 4. Cargar el controlador correspondiente
// Este switch actúa como el "directorio" de tu aplicación.
// Busca la página solicitada y carga el controlador que se encarga de ella.
switch ($page) {
    case 'home':
        // Si la URL es ?page=home o no tiene nada, carga el controlador de la página principal.
        require_once __DIR__ . '/../src/controllers/shop/indexController.php';
        break;

    case 'products':
        // Si la URL es ?page=products, carga el controlador que maneja la lista de productos.
        require_once __DIR__ . '/../src/controllers/product/productController.php';
        break;

    case 'login':
        // Si la URL es ?page=login, carga el controlador de inicio de sesión.
        require_once __DIR__ . '/../src/controllers/auth/loginController.php';
        break;
    
    case 'cart':
        // Si la URL es ?page=cart, carga el controlador del carrito de compras.
        require_once __DIR__ . '/../src/controllers/shop/cartController.php';
        break;

    // Puedes añadir todas las páginas que necesites...
    // case 'contact':
    //    require_once __DIR__ . '/../src/controllers/contactController.php';
    //    break;

    default:
        // Si se pide una página que no existe en nuestro switch, mostramos un error 404.
        http_response_code(404);
        require_once __DIR__ . '/../views/error/404.html'; // Muestra una página de error amigable
        break;
}