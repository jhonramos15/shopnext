<?php
// En: src/models/Admin/AdminClientModel.php

namespace App\Models\Admin;

use Config\Database;
use Exception;

class AdminClientModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene las estadísticas de clientes para las tarjetas.
     */
    public function getClientStats(): array {
        $stats = [];

        $stats['total_usuarios'] = $this->conn->query("SELECT COUNT(*) as total FROM usuario WHERE rol = 'cliente'")->fetch_assoc()['total'] ?? 0;
        $stats['nuevos_usuarios'] = $this->conn->query("SELECT COUNT(*) as nuevos FROM usuario WHERE rol = 'cliente' AND fecha_registro >= CURDATE() - INTERVAL 7 DAY")->fetch_assoc()['nuevos'] ?? 0;

        // Cálculo del cambio porcentual
        $usuarios_anteriores = $stats['total_usuarios'] - $stats['nuevos_usuarios'];
        if ($usuarios_anteriores > 0) {
            $stats['cambio_porcentual'] = ($stats['nuevos_usuarios'] / $usuarios_anteriores) * 100;
        } elseif ($stats['nuevos_usuarios'] > 0) {
            $stats['cambio_porcentual'] = 100;
        } else {
            $stats['cambio_porcentual'] = 0;
        }

        return $stats;
    }

    /**
     * Obtiene la lista completa de todos los clientes.
     */
    public function getAllClients(): array {
        $sql = "SELECT u.id_usuario, c.nombre, u.correo_usuario, u.estado, u.fecha_registro 
                FROM usuario u 
                JOIN cliente c ON u.id_usuario = c.id_usuario 
                WHERE u.rol = 'cliente'
                ORDER BY u.fecha_registro DESC";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}