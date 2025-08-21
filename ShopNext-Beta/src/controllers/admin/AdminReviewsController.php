<?php
// En: src/controllers/Admin/AdminReviewsController.php

namespace App\Controllers\Admin;

use App\Models\Admin\AdminReviewsModel;
use Exception;

class AdminReviewsController {

    /**
     * Esta es la "caja de herramientas" del controlador.
     * Guardará nuestra instancia del modelo para que todos los métodos la usen.
     * @var AdminReviewsModel
     */
    private $reviewModel;

    /**
     * El constructor. Se ejecuta al crear el controlador.
     * Su trabajo es "comprar" el martillo y guardarlo en la caja de herramientas.
     */
    public function __construct() {
        $this->reviewModel = new AdminReviewsModel();
    }
    
    public function showReviewsPage() {
        try {
            // Ahora usamos el modelo que ya está en nuestra "caja de herramientas".
            $stats = $this->reviewModel->getReviewStats();
            $reviews = $this->reviewModel->getAllReviews();
            
            $data = [
                'admin_nombre' => 'Brayan',
                'stats'        => $stats,
                'reviews'      => $reviews
            ];
            require_once __DIR__ . '/../../../views/dashboard/admin/reviews.php';
        } catch (Exception $e) {
            die("Error en el controlador de reseñas del admin: " . $e->getMessage());
        }
    }

    /**
     * Maneja la aprobación de una reseña.
     */
    public function handleUpdate() {
        header('Content-Type: application/json');
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $id_resena = isset($data['id_resena']) ? (int)$data['id_resena'] : 0;
        $estado = $data['estado'] ?? '';

        if ($id_resena > 0 && in_array($estado, ['aprobado', 'rechazado'])) {
            // Ahora el martillo SÍ está en la caja de herramientas y se puede usar.
            $success = $this->reviewModel->updateReviewStatus($id_resena, $estado);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error en la base de datos.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        }
        
        exit;
    }
}