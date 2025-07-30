<?php
namespace App\Controllers\Shop;

// Importamos las herramientas que vamos a usar
use App\Models\ProductsModel;
use App\Core\SessionManager;

class HomeController {

    public function home() {
        // 1. Inicia la sesión
        SessionManager::start();

        // 2. Prepara los modelos
        $productModel = new ProductsModel();

        // 3. Crea TODAS las variables para la vista
        $usuario_logueado = SessionManager::isLoggedIn(); // <- Aquí la creamos (será true o false)
        $latestProducts = $productModel->getLatestProducts(8);
        $bestSellingProducts = $productModel->getBestSellingProducts(4);

        // 4. Llama a la vista
        // Todas las variables creadas arriba ($usuario_logueado, $latestProducts, etc.)
        // estarán disponibles automáticamente en home.php
        require_once __DIR__ . '/../../../views/pages/home.php';
    }
}
?>