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

    public function getClientData() {
        header('Content-Type: application/json');
        $id_usuario = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id_usuario) {
            echo json_encode(['success' => false, 'message' => 'ID no válido.']);
            return;
        }
        $clientModel = new AdminClientModel();
        $client = $clientModel->findClientById($id_usuario);
        echo json_encode(['success' => !!$client, 'data' => $client]);
    }

    /**
     * ✅ NUEVO: Maneja la actualización de un cliente.
     */
    public function handleUpdate() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_usuario = $data['id_usuario'] ?? 0;

            $clientModel = new AdminClientModel();
            $success = $clientModel->updateClient($id_usuario, $data);
            
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * ✅ NUEVO: Maneja el borrado lógico de un cliente.
     */
    public function handleDelete() {
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $id_usuario = $data['id_usuario'] ?? 0;

            $clientModel = new AdminClientModel();
            $success = $clientModel->deleteClient($id_usuario);
            
            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}