<?php
// En: src/models/Vendedor/IncomeModel.php

namespace App\Models\Vendedor;

use Config\Database;
use Exception;

class IncomeModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene los datos básicos del vendedor.
     */
    public function getVendedorInfo(int $id_usuario): ?array {
        $stmt = $this->conn->prepare("SELECT id_vendedor, nombre FROM vendedor WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }

    /**
     * Obtiene las estadísticas de ingresos para las tarjetas.
     */
    public function getIncomeStats(int $id_vendedor): array {
        $stats = [];

        // Ingresos de los últimos 30 días
        $stmt30 = $this->conn->prepare(
            "SELECT SUM(dp.cantidad * dp.precio_unitario) as total
             FROM detalle_pedido dp
             JOIN pedido p ON dp.id_pedido = p.id_pedido
             WHERE p.id_vendedor = ? AND p.fecha >= CURDATE() - INTERVAL 30 DAY"
        );
        $stmt30->bind_param("i", $id_vendedor);
        $stmt30->execute();
        $stats['ingresos_30_dias'] = $stmt30->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt30->close();

        // Ingresos de los últimos 7 días
        $stmt7 = $this->conn->prepare(
            "SELECT SUM(dp.cantidad * dp.precio_unitario) as total
             FROM detalle_pedido dp
             JOIN pedido p ON dp.id_pedido = p.id_pedido
             WHERE p.id_vendedor = ? AND p.fecha >= CURDATE() - INTERVAL 7 DAY"
        );
        $stmt7->bind_param("i", $id_vendedor);
        $stmt7->execute();
        $stats['ingresos_7_dias'] = $stmt7->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt7->close();

        return $stats;
    }

    /**
     * Obtiene la lista de las últimas ventas para la tabla.
     */
    public function getLatestSales(int $id_vendedor): array {
        $stmt = $this->conn->prepare(
           "SELECT c.nombre AS nombre_cliente, prod.nombre_producto, dp.precio_unitario,
                   u.correo_usuario AS email_cliente, p.estado
            FROM detalle_pedido dp
            JOIN pedido p ON dp.id_pedido = p.id_pedido
            JOIN producto prod ON dp.id_producto = prod.id_producto
            JOIN cliente c ON p.id_cliente = c.id_cliente
            JOIN usuario u ON c.id_usuario = u.id_usuario
            WHERE p.id_vendedor = ?
            ORDER BY p.fecha DESC, p.id_pedido DESC
            LIMIT 8"
        );
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }
}