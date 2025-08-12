<?php
// En: src/controllers/vendedor/ProductController.php

namespace App\Controllers\Vendedor;
use App\Models\Vendedor\ProductModel;
use Exception;

class ProductController {

    public function showProductsPage() {
        try {
            $productModel = new ProductModel();
            
            $id_usuario_session = $_SESSION['id_usuario'];
            $vendedorInfo = $productModel->getVendedorInfo($id_usuario_session);

            if (!$vendedorInfo) {
                throw new Exception("Perfil de vendedor no encontrado para este usuario.");
            }
            
            $id_vendedor = $vendedorInfo['id_vendedor'];

            // Pedimos al modelo todos los datos que necesita la vista
            $stats = $productModel->getProductStats($id_vendedor);
            $products = $productModel->getProductsByVendedor($id_vendedor);

            // Empaquetamos todo en un solo array $data
            $data = [
                'nombre_vendedor'    => $vendedorInfo['nombre'],
                'stats'              => $stats,
                'products'           => $products
            ];
            
            // Y se lo pasamos a la vista
            require_once __DIR__ . '/../../../views/dashboard/vendedor/productos.php';

        } catch (Exception $e) {
            // Manejo de errores
            die("Error en el controlador de productos: " . $e->getMessage());
        }
    }

    public function showUploadForm() {
        try {
            $productModel = new ProductModel();
            $id_usuario_session = $_SESSION['id_usuario'];
            $vendedorInfo = $productModel->getVendedorInfo($id_usuario_session);

            if (!$vendedorInfo) {
                throw new Exception("Perfil de vendedor no encontrado.");
            }
            
            $data = [
                'nombre_vendedor' => $vendedorInfo['nombre']
            ];
            
            require_once __DIR__ . '/../../../views/dashboard/vendedor/upload-products.php';

        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    /**
     * ✅ NUEVO MÉTODO: Procesa los datos del formulario de nuevo producto.
     */
    public function handleUpload() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=seller&page=upload-product');
            exit;
        }

        try {
            $productModel = new ProductModel();
            $id_usuario_session = $_SESSION['id_usuario'];
            $vendedorInfo = $productModel->getVendedorInfo($id_usuario_session);
            $id_vendedor = $vendedorInfo['id_vendedor'];

            // --- Lógica para subir la imagen ---
            $image_name = 'default.png';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'][0] == 0) {
                $upload_dir = __DIR__ . '/../../../public/uploads/products/';
                $extension = pathinfo($_FILES['imagen']['name'][0], PATHINFO_EXTENSION);
                $image_name = 'prod_' . uniqid() . '.' . $extension;
                
                if (!move_uploaded_file($_FILES['imagen']['tmp_name'][0], $upload_dir . $image_name)) {
                   throw new Exception('Error al mover el archivo de imagen.');
                }
            }

            // --- Lógica para crear el producto en la BD ---
            $success = $productModel->createProduct($_POST, $id_vendedor, $image_name);

            if ($success) {
                header('Location: ' . BASE_URL . 'index.php?action=seller&page=products&status=success');
            } else {
                header('Location: ' . BASE_URL . 'index.php?action=seller&page=upload-product&status=error');
            }
            exit;

        } catch (Exception $e) {
            die("Error al procesar el producto: " . $e->getMessage());
        }
    }
}