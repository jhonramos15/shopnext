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

    public function getRecentTickets(): array {
        // ✅ MODIFICADO: Solo mostramos tickets que no estén cerrados.
        $sql = "SELECT t.id_ticket, c.nombre AS nombre_cliente, t.asunto, t.fecha_creacion, t.prioridad, t.estado
                FROM tickets t
                JOIN usuario u ON t.id_usuario = u.id_usuario
                JOIN cliente c ON u.id_usuario = c.id_usuario
                WHERE t.estado != 'Cerrado'
                ORDER BY FIELD(t.prioridad, 'Alta', 'Media', 'Baja'), t.fecha_creacion DESC
                LIMIT 25";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * ✅ NUEVO: Encuentra un ticket por su ID.
     */
    public function findTicketById(int $id_ticket): ?array {
        $stmt = $this->conn->prepare("SELECT id_ticket, asunto, estado, prioridad FROM tickets WHERE id_ticket = ?");
        $stmt->bind_param("i", $id_ticket);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }

    /**
     * ✅ NUEVO: Actualiza el estado y la prioridad de un ticket.
     */
    public function updateTicket(int $id_ticket, array $data): bool {
        $allowed_statuses = ['Abierto', 'En progreso', 'Resuelto', 'Cerrado'];
        $allowed_priorities = ['Baja', 'Media', 'Alta'];

        if (!in_array($data['estado'], $allowed_statuses) || !in_array($data['prioridad'], $allowed_priorities)) {
            return false; // Datos no válidos
        }

        $stmt = $this->conn->prepare("UPDATE tickets SET estado = ?, prioridad = ? WHERE id_ticket = ?");
        $stmt->bind_param("ssi", $data['estado'], $data['prioridad'], $id_ticket);
        return $stmt->execute();
    }

    /**
     * ✅ NUEVO: Cierra un ticket (lo marca como 'Cerrado').
     */
    public function closeTicket(int $id_ticket): bool {
        $stmt = $this->conn->prepare("UPDATE tickets SET estado = 'Cerrado' WHERE id_ticket = ?");
        $stmt->bind_param("i", $id_ticket);
        return $stmt->execute();
    }
}