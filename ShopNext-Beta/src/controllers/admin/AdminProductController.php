<?php
// En: src/controllers/Admin/AdminProductController.php

namespace App\Controllers\Admin;

use App\Models\Admin\AdminProductModel;
use Exception;

class AdminProductController {

    public function showProductsPage() {
        try {
            $productModel = new AdminProductModel();
            
            // Pedimos todos los datos que la vista necesita
            $stats = $productModel->getProductStats();
            $products = $productModel->getAllProducts();

            // Empaquetamos todo en el array $data
            $data = [
                'admin_nombre' => 'Brayan', // Temporalmente estático
                'stats'        => $stats,
                'products'     => $products
            ];
            
            // Le pasamos los datos a la vista
            require_once __DIR__ . '/../../../views/dashboard/admin/productos.php';

        } catch (Exception $e) {
            die("Error en el controlador de productos del admin: " . $e->getMessage());
        }
    }
}