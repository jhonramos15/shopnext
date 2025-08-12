<?php
// En: src/models/vendedor/OrderModel.php

namespace App\Models\Vendedor; // <-- Su propio namespace de Modelo

use Config\Database;
use Exception;

class OrderModel { // <-- Su propio nombre de clase

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }
    
    public function getVendedorInfo(int $id_usuario): ?array {
        $stmt = $this->conn->prepare("SELECT id_vendedor, nombre FROM vendedor WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }

    public function getOrderStats(int $id_vendedor): array {
        // TODO: Reemplazar con consultas SQL reales
        $stats = [
            'pedidos_hoy' => 42,
            'pedidos_pendientes' => 18,
            'pedidos_completados' => 1287
        ];
        return $stats;
    }

    public function getOrdersByVendedor(int $id_vendedor): array {
        $sql = "SELECT p.id_pedido, p.fecha, c.nombre AS nombre_cliente, p.estado, 
                (SELECT SUM(dp.cantidad * dp.precio_unitario) FROM detalle_pedido dp WHERE dp.id_pedido = p.id_pedido) AS total
                FROM pedido p
                JOIN cliente c ON p.id_cliente = c.id_cliente
                WHERE p.id_vendedor = ? ORDER BY p.fecha DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }
}