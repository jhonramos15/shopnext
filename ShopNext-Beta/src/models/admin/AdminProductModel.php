<?php
// En: src/models/Admin/AdminProductModel.php

namespace App\Models\Admin;

use Config\Database;
use Exception;

class AdminProductModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    public function getAllCategories(): array {
        $sql = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene las estadísticas de productos para las tarjetas.
     */
    public function getProductStats(): array {
        $stats = [];
        // ✅ MODIFICADO: Las estadísticas ahora solo cuentan productos activos.
        $stats['total_productos'] = $this->conn->query("SELECT COUNT(*) as total FROM producto WHERE estado = 'activo'")->fetch_assoc()['total'] ?? 0;
        $stats['valor_inventario'] = $this->conn->query("SELECT SUM(precio * stock) as total FROM producto WHERE estado = 'activo'")->fetch_assoc()['total'] ?? 0;
        $stats['productos_agotados'] = $this->conn->query("SELECT COUNT(*) as total FROM producto WHERE stock = 0 AND estado = 'activo'")->fetch_assoc()['total'] ?? 0;
        return $stats;
    }

    public function getAllProducts(): array {
        // ✅ MODIFICADO: La consulta ahora solo trae productos con estado 'activo'.
        $sql = "SELECT
                    p.id_producto, p.nombre_producto, p.precio, p.categoria, p.stock,
                    v.nombre AS nombre_vendedor
                FROM producto p
                JOIN vendedor v ON p.id_vendedor = v.id_vendedor
                WHERE p.estado = 'activo'
                ORDER BY p.id_producto DESC";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }


        public function findProductById(int $id_producto): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM producto WHERE id_producto = ?");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }

    /**
     * ✅ NUEVO: Actualiza un producto en la base de datos.
     */
    public function updateProduct(int $id_producto, array $data): bool {
        $sql = "UPDATE producto SET nombre_producto = ?, categoria = ?, precio = ?, stock = ? WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssdii",
            $data['nombre_producto'],
            $data['categoria'],
            $data['precio'],
            $data['stock'],
            $id_producto
        );
        return $stmt->execute();
    }

    /**
     * ✅ NUEVO: Elimina un producto de la base de datos.
     */
    public function deleteProduct(int $id_producto): bool {
        $stmt = $this->conn->prepare("UPDATE producto SET estado = 'inactivo' WHERE id_producto = ?");
        $stmt->bind_param("i", $id_producto);
        return $stmt->execute();
    }
}