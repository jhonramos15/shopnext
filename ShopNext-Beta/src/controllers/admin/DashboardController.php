<?php
// En: src/controllers/Admin/AdminDashboardController.php

namespace App\Controllers\Admin;

use App\Models\Admin\AdminDashboardModel;
use Exception;

class DashboardController {

    public function showDashboardPage() {
        try {
            $dashboardModel = new AdminDashboardModel();

            // Pedimos todos los datos necesarios al modelo
            $stats = $dashboardModel->getDashboardStats();
            $recentOrders = $dashboardModel->getRecentOrders();

            // Empaquetamos todo en un solo array $data para la vista
            $data = [
                'admin_nombre'   => 'Brayan', // Puedes obtenerlo de la sesión o BD si lo necesitas
                'stats'          => $stats,
                'recent_orders'  => $recentOrders
            ];
            
            // Y se lo pasamos a la vista
            require_once __DIR__ . '/../../../views/dashboard/admin-view.php';

        } catch (Exception $e) {
            die("Error fatal en el controlador del Admin: " . $e->getMessage());
        }
    }
}