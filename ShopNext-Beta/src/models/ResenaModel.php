<?php
// En: src/models/ResenaModel.php

namespace App\Models;

use Config\Database;

class ResenaModel { // ✅ ¡CORRECCIÓN! El nombre de la clase ahora es el correcto.

    private $conn;

    public function __construct() {
        $database = new \Config\Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Guarda una nueva reseña en la base de datos.
     */
    public function create(int $id_producto, int $id_cliente, string $nombre_usuario, int $puntuacion, string $comentario): bool {
        $sql = "INSERT INTO resenas (id_producto, id_cliente, nombre_usuario, puntuacion, comentario, fecha_creacion) VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta para crear reseña: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("iisis", $id_producto, $id_cliente, $nombre_usuario, $puntuacion, $comentario);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error al ejecutar la consulta para crear reseña: " . $stmt->error);
            return false;
        }
    }
}