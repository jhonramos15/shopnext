<?php
// En: src/controllers/vendedor/OrderController.php

namespace App\Controllers\Vendedor;

// ✅ ¡CORRECCIÓN CLAVE!
// Le damos la ruta completa y correcta de dónde vive el OrderModel.
use App\Models\Vendedor\OrderModel;
use Exception;

class OrderController {

    public function showOrdersPage() {
        try {
            // Ahora PHP sabe exactamente qué clase 'OrderModel' crear.
            $orderModel = new OrderModel();
            
            $id_usuario_session = $_SESSION['id_usuario'];
            $vendedorInfo = $orderModel->getVendedorInfo($id_usuario_session);

            if (!$vendedorInfo) {
                throw new Exception("Perfil de vendedor no encontrado para este usuario.");
            }
            
            $id_vendedor = $vendedorInfo['id_vendedor'];

            $stats = $orderModel->getOrderStats($id_vendedor);
            $orders = $orderModel->getOrdersByVendedor($id_vendedor);

            // Empaquetamos todo en un solo array $data
            $data = [
                'nombre_vendedor' => $vendedorInfo['nombre'],
                'stats'           => $stats,
                'orders'          => $orders
            ];
            
            // Y se lo pasamos a la vista correcta
            require_once __DIR__ . '/../../../views/dashboard/vendedor/orders.php';

        } catch (Exception $e) {
            die("Error en el controlador de pedidos: " . $e->getMessage());
        }
    }
}