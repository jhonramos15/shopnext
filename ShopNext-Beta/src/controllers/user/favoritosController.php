<?php
// En src/controllers/user/FavoritesController.php

namespace App\Controllers\User;

use App\Models\FavoritosModel;
use App\Core\SessionManager; // Usamos nuestro manejador de sesión

class FavoritesController {

    public function toggleFavorite() {
        header('Content-Type: application/json');
        SessionManager::start();

        // 1. Verificamos si el usuario ha iniciado sesión
        if (!SessionManager::isLoggedIn() || SessionManager::get('rol') !== 'cliente') {
            echo json_encode(['success' => false, 'error' => 'login_required']);
            exit;
        }

        // 2. Verificamos que nos hayan enviado un ID de producto válido
        if (!isset($_POST['id_producto']) || !is_numeric($_POST['id_producto'])) {
            echo json_encode(['success' => false, 'error' => 'ID de producto inválido.']);
            exit;
        }

        try {
            $id_usuario = SessionManager::get('id_usuario');
            $id_producto = (int)$_POST['id_producto'];

            // 3. Creamos el modelo y le pasamos la orden
            $favoritesModel = new FavoritosModel();
            $action = $favoritesModel->toggle($id_usuario, $id_producto);

            // 4. Respondemos al JavaScript con la acción realizada
            echo json_encode(['success' => true, 'action' => $action, 'message' => "Acción '$action' completada."]);

        } catch (\Exception $e) {
            // Si algo explota en el modelo, lo atrapamos aquí
            http_response_code(500); // Error de servidor
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}