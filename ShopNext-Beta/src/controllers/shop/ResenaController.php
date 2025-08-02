<?php
// src/controllers/shop/ResenaController.php
namespace App\Controllers\Shop;

use App\Models\ResenaModel;
use App\Models\ClienteModel;

/**
 * Controlador para gestionar las acciones relacionadas con las reseñas.
 */
class ResenaController {

    /**
     * Procesa el envío del formulario para guardar una nueva reseña.
     */
    public function guardar() {
        session_start();
        header('Content-Type: application/json');

        // Validar que el usuario haya iniciado sesión
        if (!isset($_SESSION['id_usuario'])) {
            http_response_code(401); // No autorizado
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para dejar una reseña.']);
            exit;
        }

        // Obtener la información del cliente usando el modelo
        $clienteModel = new ClienteModel();
        $cliente = $clienteModel->findByUsuarioId($_SESSION['id_usuario']);

        if (!$cliente) {
            http_response_code(403); // Prohibido
            echo json_encode(['success' => false, 'message' => 'Tu cuenta no está asociada a un perfil de cliente.']);
            exit;
        }

        // Validar y sanear los datos del formulario
        $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
        $puntuacion = filter_input(INPUT_POST, 'puntuacion', FILTER_VALIDATE_INT);
        $comentario = trim(filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING));

        if (!$id_producto || !$puntuacion || $puntuacion < 1 || $puntuacion > 5) {
            http_response_code(400); // Solicitud incorrecta
            echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos.']);
            exit;
        }

        // Usar el modelo de reseñas para guardar los datos
        $resenaModel = new ResenaModel();
        $success = $resenaModel->create(
            $id_producto,
            $cliente['id_cliente'],
            $cliente['nombre'],
            $puntuacion,
            $comentario
        );

        // Enviar respuesta JSON
        if ($success) {
            echo json_encode(['success' => true, 'message' => '¡Gracias por tu reseña!']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar la reseña. Inténtalo más tarde.']);
        }
    }
}
