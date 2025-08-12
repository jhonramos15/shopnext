<?php

namespace App\Models\Vendedor;

use Config\Database;
use Exception;

class ProductModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene los datos básicos del vendedor para la vista.
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
     * Obtiene las estadísticas de productos para las tarjetas.
     */
    public function getProductStats(int $id_vendedor): array {
        $stats = [];

        // Total de productos
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM producto WHERE id_vendedor = ?");
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $stats['total_productos'] = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt->close();

        // Valor del inventario
        $stmt = $this->conn->prepare("SELECT SUM(precio * stock) as valor_total FROM producto WHERE id_vendedor = ?");
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $stats['valor_inventario'] = $stmt->get_result()->fetch_assoc()['valor_total'] ?? 0;
        $stmt->close();

        // Productos agotados
        $stmt = $this->conn->prepare("SELECT COUNT(*) as agotados FROM producto WHERE id_vendedor = ? AND stock = 0");
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $stats['productos_agotados'] = $stmt->get_result()->fetch_assoc()['agotados'] ?? 0;
        $stmt->close();

        return $stats;
    }

    /**
     * Obtiene la lista completa de productos de un vendedor.
     */
    public function getProductsByVendedor(int $id_vendedor): array {
        $stmt = $this->conn->prepare(
            "SELECT id_producto, nombre_producto, precio, categoria, stock, ruta_imagen 
             FROM producto WHERE id_vendedor = ?"
        );
        $stmt->bind_param("i", $id_vendedor);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado;
    }

    public function createProduct(array $data, int $id_vendedor, string $image_path): bool {
        $sql = "INSERT INTO producto (nombre_producto, categoria, descripcion, precio, stock, id_vendedor, ruta_imagen) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bind_param(
            "sssdiis", 
            $data['titulo'], 
            $data['categoria'], 
            $data['descripcion'], 
            $data['precio'], 
            $data['stock'], 
            $id_vendedor, 
            $image_path
        );
        
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
}