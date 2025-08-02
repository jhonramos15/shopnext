<?php
// En app/Controllers/Shop/ProductController.php

namespace App\Controllers\Shop;

use App\Models\ProductsModel;
use App\Core\SessionManager;

class ProductController {

    public function show($id) {
        // 1. Prepara el modelo
        $productModel = new ProductsModel();

        // 2. Pide al modelo que busque el producto por su ID
        $producto = $productModel->findProductById($id);

        // 3. Verifica si el producto existe
        if (!$producto) {
            // Si no existe, muestra la página de error 404
            http_response_code(404);
            require __DIR__ . '/../../../views/error/404.html';
            return; // Termina la ejecución
        }

        // ✅ MEJORA: Preparamos un paquete de datos para la vista
        $data = [
            'usuario_logueado' => SessionManager::isLoggedIn(),
            'producto' => $producto,
            // Podríamos añadir productos relacionados en el futuro
            'productos_relacionados' => [] 
        ];

        // 4. Llama a la vista y le pasa el paquete de datos
        require_once __DIR__ . '/../../../views/pages/producto-detalle.php';
    }
}