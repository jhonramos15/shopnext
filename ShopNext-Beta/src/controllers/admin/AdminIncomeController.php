<?php
// En: src/controllers/Admin/AdminIncomeController.php

namespace App\Controllers\Admin;

use App\Models\Admin\AdminIncomeModel;
use Exception;

class AdminIncomeController {

    public function showIncomePage() {
        try {
            $incomeModel = new AdminIncomeModel();
            
            $stats = $incomeModel->getIncomeStats();
            $orders = $incomeModel->getRecentOrders();

            $data = [
                'admin_nombre' => 'Brayan', // Temporal
                'stats'        => $stats,
                'orders'       => $orders
            ];
            
            require_once __DIR__ . '/../../../views/dashboard/admin/income.php';

        } catch (Exception $e) {
            die("Error en el controlador de ingresos del admin: " . $e->getMessage());
        }
    }

    public function getOrderData() {
        header('Content-Type: application/json');
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID de pedido no proporcionado o no válido.']);
            return;
        }
        $id_pedido = (int)$_GET['id'];
        $incomeModel = new AdminIncomeModel();
        $order = $incomeModel->findOrderById($id_pedido);
        echo json_encode(['success' => !!$order, 'data' => $order]);
    }

    /**
     * ✅ NUEVO: Maneja la actualización del estado de un pedido.
     */
    public function handleUpdate() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_pedido = $data['id_pedido'] ?? 0;
            $estado = $data['estado'] ?? '';
            $incomeModel = new AdminIncomeModel();
            $success = $incomeModel->updateOrderStatus($id_pedido, $estado);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * ✅ NUEVO: Maneja la cancelación de un pedido (borrado lógico).
     */
    public function handleDelete() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_pedido = $data['id_pedido'] ?? 0;
            $incomeModel = new AdminIncomeModel();
            $success = $incomeModel->updateOrderStatus($id_pedido, 'Cancelado');
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
