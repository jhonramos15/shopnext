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

    /**
     * Obtiene las estadísticas de productos para las tarjetas.
     */
    public function getProductStats(): array {
        $stats = [];

        $stats['total_productos'] = $this->conn->query("SELECT COUNT(*) as total FROM producto")->fetch_assoc()['total'] ?? 0;
        $stats['valor_inventario'] = $this->conn->query("SELECT SUM(precio * stock) as total FROM producto")->fetch_assoc()['total'] ?? 0;
        $stats['productos_agotados'] = $this->conn->query("SELECT COUNT(*) as total FROM producto WHERE stock = 0")->fetch_assoc()['total'] ?? 0;

        return $stats;
    }

    /**
     * Obtiene la lista completa de todos los productos de la tienda.
     */
    public function getAllProducts(): array {
        $sql = "SELECT
                    p.id_producto, p.nombre_producto, p.precio, p.categoria, p.stock,
                    v.nombre AS nombre_vendedor
                FROM producto p
                JOIN vendedor v ON p.id_vendedor = v.id_vendedor
                ORDER BY p.id_producto DESC";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}