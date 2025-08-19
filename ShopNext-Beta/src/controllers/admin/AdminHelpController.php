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

    /**
     * ✅ NUEVO: Obtiene los datos de un ticket para el modal.
     */
    public function getTicketData() {
        header('Content-Type: application/json');
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID de ticket no válido.']);
            return;
        }
        $id_ticket = (int)$_GET['id'];
        $helpModel = new AdminHelpModel();
        $ticket = $helpModel->findTicketById($id_ticket);
        echo json_encode(['success' => !!$ticket, 'data' => $ticket]);
    }

    /**
     * ✅ NUEVO: Maneja la actualización de un ticket.
     */
    public function handleUpdate() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_ticket = $data['id_ticket'] ?? 0;
            $helpModel = new AdminHelpModel();
            $success = $helpModel->updateTicket($id_ticket, $data);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * ✅ NUEVO: Maneja el cierre de un ticket.
     */
    public function handleDelete() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_ticket = $data['id_ticket'] ?? 0;
            $helpModel = new AdminHelpModel();
            $success = $helpModel->closeTicket($id_ticket);
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}