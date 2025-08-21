<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
use App\Controllers\Auth\LoginController; // Controlador del login
use App\Controllers\Auth\RegistroController; // Controlador del registro
use App\Controllers\Shop\HomeController; // Controlador del homepage
use App\Controllers\User\FavoritesController; // Controlador de Favoritos
use App\Core\SessionManager; // Controlador que administra las sesiones
use App\Controllers\Shop\ProductController; // Controlador de los productos
use App\Controllers\Shop\PagesController; // Controlador de varias páginas como, contacto y acerca de
use App\Controllers\User\AccountController; // Controlador de la cuenta del usuario
use App\Controllers\Shop\SearchController;
use App\Controllers\Shop\CartController;
use App\Controllers\Shop\ResenaController;
use App\Controllers\Auth\VerificarTokenController; 
use App\Guard\AuthGuard;  
use App\Controllers\Vendedor\DashboardController as VendedorDashboard;
use App\Controllers\Vendedor\ProductController as VendedorProduct;
use App\Controllers\Vendedor\OrderController as VendedorOrder;
use App\Controllers\Vendedor\IncomeController as VendedorIncome;
use App\Controllers\Admin\DashboardController as AdminDashboard;
use App\Controllers\Admin\AdminProductController as AdminProduct;
use App\Controllers\Admin\AdminClientController as AdminClient;
use App\Controllers\Admin\AdminHelpController as AdminHelp;
use App\Controllers\Admin\AdminSellerController as AdminSeller;
use App\Controllers\Admin\AdminIncomeController as AdminIncome;
use App\Controllers\Admin\AdminReviewsController as AdminReviews;

SessionManager::start();

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

    case 'verify':
        $controller = new VerificarTokenController();
        $controller->handleVerification();
        break;

    case 'signup':  // Muestra el formulario de registro
        // ✅ CORRECCIÓN: Cargamos directamente la vista.
        require_once __DIR__ . '/../views/auth/sign-up.html';
        break;

    case 'about':  // Muestra la página "Acerca de"
        // ✅ LIMPIEZA: El controlador ya se encarga de cargar la vista, no necesitamos más.
        $controller = new PagesController();
        $controller->about();
        break;
        
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

    case 'contact':
        // Carga directamente la vista de contacto
        require_once __DIR__ . '/../views/pages/contact.html';
        break;

    case 'product-detail':
        // 1. Obtenemos el ID del producto de la URL y lo sanitizamos
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($productId > 0) {
            // 2. Creamos el controlador y llamamos al método show
            $controller = new ProductController();
            $controller->show($productId);
        } else {
            // Si no hay un ID válido, es un error.
            http_response_code(400); // Bad Request
            require __DIR__ . '/../views/error/404.html';
        }
        break;

    case 'guardar-resena':
        // Nos aseguramos de que la petición sea de tipo POST por seguridad
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\Shop\ResenaController();
            $controller->guardar();
        }
        break;

    /* ========== RUTAS PARA LA CUENTA DEL USUARIO ========== */
    case 'account':
        $controller = new AccountController();
        $controller->show();
        break;

    case 'update-profile':
        $controller = new AccountController();
        $controller->update();
        break;

    case 'search':
        $controller = new SearchController();
        $controller->handleSearch();
        break;

    /* ========== RUTAS PARA FAVORITOS ========== */
    case 'favorites': // Para mostrar la página de favoritos
        $controller = new App\Controllers\User\FavoritosController();
        $controller->show();
        break;

    case 'toggle-favorite': // Para la acción AJAX de agregar/quitar
        $controller = new App\Controllers\User\FavoritosController();
        $controller->toggleFavorite();
    break;

    case 'cart':
        $controller = new CartController();
        $controller->show();
        break;

    case 'cart-api':
        // Esta ruta manejará todas las acciones AJAX del carrito
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new CartController();
            $controller->handleApiAction();
        }
        break;

    /* ========== RUTAS PARA DASHBOARDS (NUEVO) ========== */

    case 'seller':
        // 1. Proteger TODA la sección de una vez.
        AuthGuard::redirectIfNotAuthenticated();
        // Aquí también podrías verificar que el rol sea 'vendedor'.

        // 2. Obtener la página específica que se solicita dentro de la sección.
        // Si no se especifica, por defecto será el dashboard.
        $page = $_GET['page'] ?? 'dashboard';

        // 3. Un mini-router solo para la sección del vendedor.
        switch ($page) {
            case 'dashboard':
                $controller = new VendedorDashboard();
                $controller->showDashboard();
                break;
            
            case 'productos':
                $controller = new VendedorProduct();
                $controller->showProductsPage();
                break;
            
            case 'income': // ✅ ¡NUEVA RUTA!
                $controller = new VendedorIncome();
                $controller->showIncomePage();
                break;
            
            case 'upload-product': // ✅ RUTA PARA MOSTRAR EL FORMULARIO
                $controller = new VendedorProduct();
                $controller->showUploadForm();
                break;

            case 'upload-action': // ✅ RUTA PARA PROCESAR EL FORMULARIO
                $controller = new VendedorProduct();
                $controller->handleUpload();
                break;
            case 'orders':
                $controller = new VendedorOrder();
                $controller->showOrdersPage();
                break;
            
            default:
                // Si la página no existe dentro de la sección de vendedor
                http_response_code(404);
                require __DIR__ . '/../views/error/404.html';
                break;
        }
        break; 

    // --- SECCIÓN COMPLETA DEL ADMIN ---
    case 'admin':
        AuthGuard::redirectIfNotAuthenticated();
        $page = $_GET['page'] ?? 'dashboard';
        
        // ✅ **LA CORRECCIÓN ESTÁ AQUÍ**
        // Leemos el parámetro 'crud' para las acciones AJAX.
        $crudAction = $_GET['crud'] ?? null;

        switch ($page) {
            case 'dashboard':
                $controller = new AdminDashboard();
                $controller->showDashboardPage();
                break;

            case 'products': // Agrupamos toda la lógica de productos aquí
                $controller = new AdminProduct();
                
                // Un segundo switch para manejar las acciones CRUD de productos
                switch ($crudAction) {
                    case 'get_data':
                        $controller->getProductData();
                        break;
                    case 'update':
                        $controller->handleUpdate();
                        break;
                    case 'delete':
                        $controller->handleDelete();
                        break;
                    default:
                        // Si no hay acción CRUD, mostramos la página normal
                        $controller->showProductsPage();
                        break;
                }
                break; // Fin del case 'products'


        case 'clients':
            $controller = new AdminClient();
            $crudAction = $_GET['crud'] ?? null;

            switch ($crudAction) {
                case 'get_data':
                    $controller->getClientData();
                    break;
                case 'update':
                    $controller->handleUpdate();
                    break;
                case 'delete':
                    $controller->handleDelete();
                    break;
                default:
                    $controller->showClientsPage();
                    break;
            }
            break; // Fin del case 'clients'

        case 'help':
            $controller = new AdminHelp();
            $crudAction = $_GET['crud'] ?? null;

            switch ($crudAction) {
                case 'get_data':
                    $controller->getTicketData();
                    break;
                case 'update':
                    $controller->handleUpdate();
                    break;
                case 'delete':
                    $controller->handleDelete();
                    break;
                default:
                    $controller->showHelpPage();
                    break;
            }
            break; // Fin del case 'help'

            case 'sellers': // ✅ ¡NUEVA RUTA!
                $controller = new AdminSeller();
                $controller->showSellersPage();
                break;

        case 'income':
            $controller = new AdminIncome();
            $crudAction = $_GET['crud'] ?? null;

            switch ($crudAction) {
                case 'get_data':
                    $controller->getOrderData();
                    break;
                case 'update':
                    $controller->handleUpdate();
                    break;
                case 'delete':
                    $controller->handleDelete();
                    break;
                default:
                    $controller->showIncomePage();
                    break;
            }
            break; // Fin del case 'income'

        case 'reviews':
            $controller = new AdminReviews();
            $crudAction = $_GET['crud'] ?? null;

            switch ($crudAction) {
                case 'update':
                    $controller->handleUpdate();
                    break;
                case 'delete':
                    $controller->handleDelete();
                    break;
                default:
                    $controller->showReviewsPage();
                    break;
            }
            break; // Fin del case 'reviews'


            default:
                http_response_code(404);
                require __DIR__ . '/../views/error/404.html';
                break;
        }
        break; // ¡Importante! Cierra el 'case: admin'
        
default:
    // Establecemos el código de respuesta HTTP correcto para "No Encontrado"
    http_response_code(404);
    
    // Cargamos nuestra página HTML personalizada para el error 404
    require __DIR__ . '/../views/error/404.html';
    break;
}
?>