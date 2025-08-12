<?php
// En: src/models/Admin/AdminSellerModel.php

namespace App\Models\Admin;

use Config\Database;
use Exception;

class AdminSellerModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene las estadísticas de vendedores para las tarjetas.
     */
    public function getSellerStats(): array {
        $stats = [];

        $stats['total_vendedores'] = $this->conn->query("SELECT COUNT(*) as total FROM usuario WHERE rol = 'vendedor'")->fetch_assoc()['total'] ?? 0;
        
        $ventas_mes_query = "SELECT SUM(dp.cantidad * dp.precio_unitario) as total
                             FROM detalle_pedido dp
                             JOIN pedido p ON dp.id_pedido = p.id_pedido
                             WHERE MONTH(p.fecha) = MONTH(CURDATE()) AND YEAR(p.fecha) = YEAR(CURDATE())";
        $stats['ventas_mes'] = $this->conn->query($ventas_mes_query)->fetch_assoc()['total'] ?? 0;

        $mejor_vendedor_query = "SELECT v.nombre, SUM(dp.cantidad * dp.precio_unitario) AS total_vendido
                                 FROM vendedor v
                                 JOIN pedido p ON v.id_vendedor = p.id_vendedor
                                 JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
                                 GROUP BY v.id_vendedor
                                 ORDER BY total_vendido DESC LIMIT 1";
        $result = $this->conn->query($mejor_vendedor_query);
        $stats['mejor_vendedor_nombre'] = ($result->num_rows > 0) ? $result->fetch_assoc()['nombre'] : 'N/A';
        
        return $stats;
    }

    /**
     * Obtiene la lista completa de todos los vendedores.
     */
    public function getAllSellers(): array {
        $sql = "SELECT u.id_usuario, v.nombre, u.correo_usuario, u.estado
                FROM usuario u
                JOIN vendedor v ON u.id_usuario = v.id_usuario
                WHERE u.rol = 'vendedor'
                ORDER BY v.nombre ASC";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}