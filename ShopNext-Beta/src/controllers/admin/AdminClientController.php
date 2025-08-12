<?php
// En: src/controllers/Admin/AdminClientController.php

namespace App\Controllers\Admin;

use App\Models\Admin\AdminClientModel;
use Exception;

class AdminClientController {

    public function showClientsPage() {
        try {
            $clientModel = new AdminClientModel();
            
            // Pedimos todos los datos que la vista necesita
            $stats = $clientModel->getClientStats();
            $clients = $clientModel->getAllClients();

            // Empaquetamos todo en el array $data
            $data = [
                'admin_nombre' => 'Brayan', // Temporalmente estático
                'stats'        => $stats,
                'clients'      => $clients
            ];
            
            // Le pasamos los datos a la vista
            require_once __DIR__ . '/../../../views/dashboard/admin/clientes.php';

        } catch (Exception $e) {
            die("Error en el controlador de clientes del admin: " . $e->getMessage());
        }
    }
}