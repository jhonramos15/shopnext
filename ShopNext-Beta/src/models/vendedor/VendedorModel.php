<?php
// En: src/models/VendedorDashboardModel.php

namespace App\Models\Vendedor;

use Config\Database; // Usamos nuestra clase de conexión
use Exception;

class VendedorModel {
    private $conn;

    public function __construct() {
        // Obtenemos la conexión a la BD como en los otros modelos
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            // Manejar error de conexión
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene el ID y el nombre del vendedor a partir del ID de usuario de la sesión.
     * @param int $id_usuario El ID del usuario logueado.
     * @return array|null Un array con id_vendedor y nombre, o null si no se encuentra.
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
     * Obtiene las estadísticas principales para las tarjetas del dashboard.
     * @param int $id_vendedor El ID del vendedor.
     * @return array Un array con los totales de ingresos, pedidos y clientes.
     */
    public function getDashboardStats(int $id_vendedor): array {
        $stats = [
            'ingresos_totales' => 0,
            'pedidos_realizados' => 0,
            'nuevos_clientes' => 0
        ];

        // 1. Ingresos Totales
        $stmt = $this->conn->prepare(
            "SELECT SUM(dp.cantidad * dp.precio_unitario) as total
             FROM detalle_pedido dp
             JOIN pedido p ON dp.id_pedido = p.id_pedido
             WHERE p.id_vendedor = ?"
        );
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $stats['ingresos_totales'] = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt->close();

        // 2. Pedidos Realizados
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM pedido WHERE id_vendedor = ?");
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $stats['pedidos_realizados'] = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt->close();

        // 3. Clientes Únicos
        $stmt = $this->conn->prepare("SELECT COUNT(DISTINCT id_cliente) as total FROM pedido WHERE id_vendedor = ?");
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $stats['nuevos_clientes'] = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt->close();

        return $stats;
    }

    /**
     * Obtiene los últimos 5 pedidos para la tabla de "Pedidos Recientes".
     * @param int $id_vendedor El ID del vendedor.
     * @return array Una lista de los pedidos recientes.
     */
    public function getPedidosRecientes(int $id_vendedor): array {
        $stmt = $this->conn->prepare(
           "SELECT p.id_pedido, prod.nombre_producto, p.estado, (dp.cantidad * dp.precio_unitario) as importe
            FROM pedido p
            JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
            JOIN producto prod ON dp.id_producto = prod.id_producto
            WHERE p.id_vendedor = ?
            ORDER BY p.fecha DESC
            LIMIT 5"
        );
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }
}