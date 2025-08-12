<?php
// En: src/controllers/Admin/AdminHelpController.php

namespace App\Controllers\Admin;

use App\Models\Admin\AdminHelpModel;
use Exception;

class AdminHelpController {

    public function showHelpPage() {
        try {
            $helpModel = new AdminHelpModel();
            
            // Pedimos todos los datos que la vista necesita
            $stats = $helpModel->getTicketStats();
            $tickets = $helpModel->getRecentTickets();

            // Empaquetamos todo en el array $data
            $data = [
                'admin_nombre' => 'Brayan', // Temporalmente estático
                'stats'        => $stats,
                'tickets'      => $tickets
            ];
            
            // Le pasamos los datos a la vista
            require_once __DIR__ . '/../../../views/dashboard/admin/help.php';

        } catch (Exception $e) {
            die("Error en el controlador de ayuda del admin: " . $e->getMessage());
        }
    }
}