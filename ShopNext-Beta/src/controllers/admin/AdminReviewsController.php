<?php
// En: src/controllers/Admin/AdminReviewsController.php

namespace App\Controllers\Admin;

use App\Models\Admin\AdminReviewsModel;
use Exception;

class AdminReviewsController {

    public function showReviewsPage() {
        try {
            $reviewsModel = new AdminReviewsModel();
            
            // Pedimos todos los datos que la vista necesita
            $stats = $reviewsModel->getReviewStats();
            $reviews = $reviewsModel->getAllReviews();

            // Empaquetamos todo en el array $data
            $data = [
                'admin_nombre' => 'Brayan', // Temporalmente estático
                'stats'        => $stats,
                'reviews'      => $reviews
            ];
            
            // Le pasamos los datos a la vista
            require_once __DIR__ . '/../../../views/dashboard/admin/reviews.php';

        } catch (Exception $e) {
            die("Error en el controlador de reseñas del admin: " . $e->getMessage());
        }
    }
}