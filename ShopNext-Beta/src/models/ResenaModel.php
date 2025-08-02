<?php
// src/models/ResenaModel.php
namespace App\Models;

use Config\Database;

/**
 * Modelo para gestionar las operaciones de las reseñas en la base de datos.
 */
class ResenaModel {
    private $conn;

    public function __construct() {
        // Se asume que el autoloader ya ha cargado la clase Database
        $database = new \Config\Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            // Manejar error de conexión de forma segura
            error_log("ResenaModel: No se pudo obtener la conexión a la base de datos.");
            // En un entorno de producción, no deberías mostrar errores detallados al usuario.
            // die("Error crítico de base de datos."); 
        }
    }

    /**
     * Guarda una nueva reseña en la base de datos.
     *
     * @param int $id_producto El ID del producto que se está reseñando.
     * @param int $id_cliente El ID del cliente que escribe la reseña.
     * @param string $nombre_usuario El nombre del cliente.
     * @param int $puntuacion La puntuación dada (de 1 a 5).
     * @param string $comentario El texto de la reseña.
     * @return bool Devuelve true si la inserción fue exitosa, false en caso contrario.
     */
    public function create(int $id_producto, int $id_cliente, string $nombre_usuario, int $puntuacion, string $comentario): bool {
        $sql = "INSERT INTO resenas (id_producto, id_cliente, nombre_usuario, puntuacion, comentario, fecha_creacion) VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta para crear reseña: " . $this->conn->error);
            return false;
        }

        // 'i' para integer, 's' para string
        $stmt->bind_param("iisis", $id_producto, $id_cliente, $nombre_usuario, $puntuacion, $comentario);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error al ejecutar la consulta para crear reseña: " . $stmt->error);
            return false;
        }
    }
}
