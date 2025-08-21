<?php
// En: src/models/Admin/AdminReviewsModel.php

namespace App\Models\Admin;

use Config\Database;
use Exception;

class AdminReviewsModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene las estadísticas de reseñas para las tarjetas.
     */
    public function getReviewStats(): array {
        $stats = [];

        $stats['total_resenas'] = $this->conn->query("SELECT COUNT(*) as total FROM resenas")->fetch_assoc()['total'] ?? 0;
        
        $avg_rating_query = "SELECT AVG(puntuacion) as promedio FROM resenas";
        $stats['calificacion_promedio'] = $this->conn->query($avg_rating_query)->fetch_assoc()['promedio'] ?? 0;

        $stats['resenas_pendientes'] = $this->conn->query("SELECT COUNT(*) as total FROM resenas WHERE estado = 'pendiente'")->fetch_assoc()['total'] ?? 0;


        return $stats;
    }

public function getAllReviews(): array {
    // ✅ MODIFICADO: Usamos LEFT JOIN para más robustez.
    $sql = "SELECT 
                r.id_resena, p.nombre_producto, c.nombre AS nombre_cliente,
                r.puntuacion, r.comentario, r.fecha_creacion, r.estado
            FROM resenas r
            LEFT JOIN producto p ON r.id_producto = p.id_producto
            LEFT JOIN cliente c ON r.id_cliente = c.id_usuario
            WHERE r.estado IN ('pendiente', 'aprobado')
            ORDER BY r.fecha_creacion DESC";
    
    $resultado = $this->conn->query($sql);

    // Buena práctica: verificar si la consulta falló
    if ($resultado === false) {
        // Puedes loggear el error: error_log($this->conn->error);
        return []; 
    }
    
    return $resultado->fetch_all(MYSQLI_ASSOC);
}

    /**
     * ✅ NUEVO: Actualiza el estado de una reseña.
     */
    public function updateReviewStatus(int $id_resena, string $estado): bool {
        $allowed_statuses = ['aprobado', 'pendiente', 'rechazado'];
        if (!in_array($estado, $allowed_statuses)) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE resenas SET estado = ? WHERE id_resena = ?");
        $stmt->bind_param("si", $estado, $id_resena);
        return $stmt->execute();
    }
}