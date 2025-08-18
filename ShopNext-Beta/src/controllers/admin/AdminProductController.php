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
        $id_producto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id_producto) {
            echo json_encode(['success' => false, 'message' => 'ID no válido.']);
            return;
        }
        $productModel = new \App\Models\Admin\AdminProductModel();
        $product = $productModel->findProductById($id_producto);
        echo json_encode(['success' => !!$product, 'data' => $product]);
    }

    /**
     * ✅ NUEVO: Maneja la actualización de un producto.
     */
    public function handleUpdate() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $id_producto = $data['id_producto'] ?? 0;

        $productModel = new \App\Models\Admin\AdminProductModel();
        $success = $productModel->updateProduct($id_producto, $data);
        
        echo json_encode(['success' => $success]);
    }

    /**
     * ✅ NUEVO: Maneja la eliminación de un producto.
     */
    public function handleDelete() {
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => 'Error desconocido al eliminar.'];

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_producto = $data['id_producto'] ?? 0;

            if ($id_producto > 0) {
                $productModel = new AdminProductModel();
                if ($productModel->deleteProduct($id_producto)) {
                    $response = ['success' => true];
                } else {
                    $response['message'] = 'La operación de borrado falló en la base de datos.';
                }
            } else {
                $response['message'] = 'ID de producto no válido.';
            }
        } catch (Exception $e) {
            // CLAVE: Capturamos el error de la base de datos (ej. clave foránea)
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                 $response['message'] = 'No se puede eliminar. El producto está asociado a pedidos o reseñas.';
            } else {
                $response['message'] = 'Error del servidor: ' . $e->getMessage();
            }
        }
        
        echo json_encode($response);
    }
}