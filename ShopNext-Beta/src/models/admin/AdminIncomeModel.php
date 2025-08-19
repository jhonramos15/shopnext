<?php
// En: src/models/Admin/AdminIncomeModel.php

namespace App\Models\Admin;

use Config\Database;
use Exception;

class AdminIncomeModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene las estadísticas de ingresos para las tarjetas.
     */
    public function getIncomeStats(): array {
        $stats = [];

        $stats['ingresos_totales'] = $this->conn->query("SELECT SUM(cantidad * precio_unitario) as total FROM detalle_pedido")->fetch_assoc()['total'] ?? 0;
        
        $ingresos_mes_query = "SELECT SUM(dp.cantidad * dp.precio_unitario) as total
                               FROM detalle_pedido dp
                               JOIN pedido p ON dp.id_pedido = p.id_pedido
                               WHERE MONTH(p.fecha) = MONTH(CURDATE()) AND YEAR(p.fecha) = YEAR(CURDATE())";
        $stats['ingresos_mes'] = $this->conn->query($ingresos_mes_query)->fetch_assoc()['total'] ?? 0;

        $stats['ventas_hoy'] = $this->conn->query("SELECT COUNT(DISTINCT id_pedido) as total FROM pedido WHERE DATE(fecha) = CURDATE()")->fetch_assoc()['total'] ?? 0;

        return $stats;
    }

    /**
     * Obtiene la lista de los últimos 20 pedidos.
     */
    public function getRecentOrders(): array {
        $sql = "SELECT p.id_pedido, p.fecha, c.nombre AS nombre_cliente, p.estado, 
                       (SELECT SUM(dp.cantidad * dp.precio_unitario) FROM detalle_pedido dp WHERE dp.id_pedido = p.id_pedido) AS total_pedido
                FROM pedido p
                JOIN cliente c ON p.id_cliente = c.id_cliente
                ORDER BY p.fecha DESC
                LIMIT 20";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    
    public function findOrderById(int $id_pedido): ?array {
        $stmt = $this->conn->prepare("SELECT id_pedido, estado FROM pedido WHERE id_pedido = ?");
        $stmt->bind_param("i", $id_pedido);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }

    /**
     * ✅ NUEVO: Actualiza el estado de un pedido.
     */
    public function updateOrderStatus(int $id_pedido, string $estado): bool {
        // Lista de estados permitidos para seguridad
        $allowed_statuses = ['Pendiente', 'Procesando', 'Enviado', 'Completado', 'Cancelado'];
        if (!in_array($estado, $allowed_statuses)) {
            return false; // Estado no válido
        }

        $stmt = $this->conn->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
        $stmt->bind_param("si", $estado, $id_pedido);
        return $stmt->execute();
    }
}