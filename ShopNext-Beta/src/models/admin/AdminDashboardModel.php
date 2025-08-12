<?php
// En: src/models/Admin/AdminDashboardModel.php

namespace App\Models\Admin;

use Config\Database;
use Exception;

class AdminDashboardModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene las estadísticas principales para las tarjetas.
     */
    public function getDashboardStats(): array {
        $stats = [];

        // Total de usuarios
        $stats['total_usuarios'] = $this->conn->query("SELECT COUNT(*) as total FROM usuario")->fetch_assoc()['total'] ?? 0;
        
        // Nuevos usuarios (últimos 7 días)
        $stats['nuevos_usuarios'] = $this->conn->query("SELECT COUNT(*) as nuevos FROM usuario WHERE fecha_registro >= CURDATE() - INTERVAL 7 DAY")->fetch_assoc()['nuevos'] ?? 0;

        // Ventas totales
        $stats['total_ventas'] = $this->conn->query("SELECT SUM(cantidad * precio_unitario) as total FROM detalle_pedido")->fetch_assoc()['total'] ?? 0;

        // Pedidos totales
        $stats['total_pedidos'] = $this->conn->query("SELECT COUNT(*) as total FROM pedido")->fetch_assoc()['total'] ?? 0;

        // Cálculo del cambio porcentual de usuarios
        $usuarios_anteriores = $stats['total_usuarios'] - $stats['nuevos_usuarios'];
        if ($usuarios_anteriores > 0) {
            $stats['cambio_porcentual_usuarios'] = ($stats['nuevos_usuarios'] / $usuarios_anteriores) * 100;
        } elseif ($stats['nuevos_usuarios'] > 0) {
            $stats['cambio_porcentual_usuarios'] = 100;
        } else {
            $stats['cambio_porcentual_usuarios'] = 0;
        }

        return $stats;
    }

    /**
     * Obtiene los últimos 5 pedidos para la tabla de "Pedidos Recientes".
     */
    public function getRecentOrders(): array {
        $sql = "SELECT p.fecha, prod.nombre_producto, p.estado, (dp.cantidad * dp.precio_unitario) AS importe
                FROM pedido p
                JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
                JOIN producto prod ON dp.id_producto = prod.id_producto
                ORDER BY p.fecha DESC, p.id_pedido DESC
                LIMIT 5";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}