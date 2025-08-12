<?php
// En: src/controllers/Admin/AdminSellerController.php

namespace App\Controllers\Admin;

use App\Models\Admin\AdminSellerModel;
use Exception;

class AdminSellerController {

    public function showSellersPage() {
        try {
            $sellerModel = new AdminSellerModel();
            
            // Pedimos todos los datos que la vista necesita
            $stats = $sellerModel->getSellerStats();
            $sellers = $sellerModel->getAllSellers();

            // Empaquetamos todo en el array $data
            $data = [
                'admin_nombre' => 'Brayan', // Temporalmente estático
                'stats'        => $stats,
                'sellers'      => $sellers
            ];
            
            // Le pasamos los datos a la vista
            require_once __DIR__ . '/../../../views/dashboard/admin/seller.php';

        } catch (Exception $e) {
            die("Error en el controlador de vendedores del admin: " . $e->getMessage());
        }
    }
}