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
            $categories = $productModel->getAllCategories();

            // Empaquetamos todo en el array $data
            $data = [
                'admin_nombre' => 'Brayan', // Temporalmente estático
                'stats'        => $stats,
                'products'     => $products,
                'categories'   => $categories
            ];
            
            // Le pasamos los datos a la vista
            require_once __DIR__ . '/../../../views/dashboard/admin/productos.php';

        } catch (Exception $e) {
            die("Error en el controlador de productos del admin: " . $e->getMessage());
        }
    }

    public function getProductData() {
        header('Content-Type: application/json');
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID de producto no válido.']);
            return;
        }
        $id_producto = (int)$_GET['id'];
        $productModel = new AdminProductModel();
        $product = $productModel->findProductById($id_producto);
        echo json_encode(['success' => !!$product, 'data' => $product]);
    }

    public function handleUpdate() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_producto = $data['id_producto'] ?? 0;
            $data['id_categoria'] = $data['categoria']; // Aseguramos que el ID de categoría se pase correctamente
            $productModel = new AdminProductModel();
            $success = $productModel->updateProduct($id_producto, $data);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function handleDelete() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_producto = $data['id_producto'] ?? 0;
            $productModel = new AdminProductModel();
            $success = $productModel->deleteProduct($id_producto);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
