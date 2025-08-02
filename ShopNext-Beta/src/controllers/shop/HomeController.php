<?php
namespace App\Controllers\Shop;

// Importamos las herramientas que vamos a usar
use App\Models\ProductsModel;
use App\Core\SessionManager;

class HomeController {

    public function home() {
        // 1. Prepara los modelos que necesitarás
        $productModel = new ProductsModel();

        // 2. Obtiene los datos principales de la base de datos
        $latestProducts = $productModel->getLatestProducts(8);
        $bestSellingProducts = $productModel->getBestSellingProducts(4);

        // 3. Enriquece los datos de los productos con sus reseñas
        foreach ($latestProducts as &$product) {
            $resenaData = $productModel->getResenaPromedioPorProducto($product['id_producto']);
            $product['resena_promedio'] = $resenaData['promedio'];
            $product['resena_total'] = $resenaData['total'];
        }
        unset($product); // Rompemos la referencia

        // ✅ MEJORA: Agrupamos todos los datos en un solo array
        // Esto hace que sea más fácil saber qué información se está enviando a la vista.
        $data = [
            'usuario_logueado' => SessionManager::isLoggedIn(),
            'latestProducts' => $latestProducts,
            'bestSellingProducts' => $bestSellingProducts,
            // Aquí podrías añadir más cosas en el futuro, como 'categorias', etc.
        ];

        $data = [
            'usuario_logueado' => SessionManager::isLoggedIn(),
            'latestProducts' => $latestProducts,
            'bestSellingProducts' => $bestSellingProducts,
        ];  

        // 4. Llama a la vista y le pasa el "paquete" de datos
        // La vista ahora usará $data['latestProducts'], $data['usuario_logueado'], etc.
        require_once __DIR__ . '/../../../views/pages/home.php';
    }
}
?>