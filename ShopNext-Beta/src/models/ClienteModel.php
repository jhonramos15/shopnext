<?php
// src/models/ClienteModel.php
namespace App\Models;

use Config\Database;

/**
 * Modelo para obtener información de los clientes.
 */
class ClienteModel {
    private $conn;

    public function __construct() {
        $database = new \Config\Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Busca los datos de un cliente a partir de su ID de usuario.
     *
     * @param int $id_usuario El ID del usuario asociado al cliente.
     * @return array|null Los datos del cliente o null si no se encuentra.
     */
    public function findByUsuarioId(int $id_usuario): ?array {
        $sql = "SELECT id_cliente, nombre FROM cliente WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta para buscar cliente: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
