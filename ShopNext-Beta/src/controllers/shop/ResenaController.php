<?php
// En: src/controllers/shop/ResenaController.php

namespace App\Controllers\Shop;

use App\Models\ResenaModel;
use App\Models\ClienteModel;

class ResenaController {

    public function guardar() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id_usuario'])) {
            // ... (manejo de error de sesión)
        }

        $clienteModel = new ClienteModel();
        $cliente = $clienteModel->findByUsuarioId($_SESSION['id_usuario']);

        if (!$cliente) {
            // ... (manejo de error de cliente no encontrado)
        }

        $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
        $puntuacion = filter_input(INPUT_POST, 'puntuacion', FILTER_VALIDATE_INT);
        
        // ✅ ¡CORRECCIÓN! Usamos la forma moderna de limpiar el texto.
        $comentario_raw = $_POST['comentario'] ?? '';
        $comentario = htmlspecialchars($comentario_raw, ENT_QUOTES, 'UTF-8');

        if (!$id_producto || !$puntuacion || $puntuacion < 1 || $puntuacion > 5) {
            // ... (manejo de error de datos inválidos)
        }

        $resenaModel = new ResenaModel();
        $success = $resenaModel->create(
            $id_producto,
            $cliente['id_cliente'],
            $cliente['nombre'],
            $puntuacion,
            $comentario // Usamos la variable ya limpia
        );

        if ($success) {
            echo json_encode(['success' => true, 'message' => '¡Gracias por tu reseña!']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar la reseña.']);
        }
    }
}