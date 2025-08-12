<?php
// En: src/models/Admin/AdminHelpModel.php

namespace App\Models\Admin;

use Config\Database;
use Exception;

class AdminHelpModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene las estadísticas de tickets para las tarjetas.
     */
    public function getTicketStats(): array {
        $stats = [];

        $stats['total_tickets'] = $this->conn->query("SELECT COUNT(*) as total FROM tickets")->fetch_assoc()['total'] ?? 0;
        $stats['nuevos_hoy'] = $this->conn->query("SELECT COUNT(*) as total FROM tickets WHERE DATE(fecha_creacion) = CURDATE()")->fetch_assoc()['total'] ?? 0;
        $stats['tickets_abiertos'] = $this->conn->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Abierto'")->fetch_assoc()['total'] ?? 0;
        $stats['tickets_urgentes'] = $this->conn->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Abierto' AND prioridad = 'Alta'")->fetch_assoc()['total'] ?? 0;
        $stats['tickets_resueltos'] = $this->conn->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Resuelto'")->fetch_assoc()['total'] ?? 0;
        $stats['resueltos_hoy'] = $this->conn->query("SELECT COUNT(*) as total FROM tickets WHERE estado = 'Resuelto' AND DATE(fecha_creacion) = CURDATE()")->fetch_assoc()['total'] ?? 0;

        return $stats;
    }

    /**
     * Obtiene la lista de los últimos 10 tickets.
     */
    public function getRecentTickets(): array {
        $sql = "SELECT t.id_ticket, c.nombre AS nombre_cliente, t.asunto, t.fecha_creacion, t.prioridad, t.estado
                FROM tickets t
                JOIN usuario u ON t.id_usuario = u.id_usuario
                JOIN cliente c ON u.id_usuario = c.id_usuario
                ORDER BY t.fecha_creacion DESC
                LIMIT 10";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}