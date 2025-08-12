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
}