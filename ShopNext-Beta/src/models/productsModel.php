<?php
require_once __DIR__ . '/../../config/database.php';

class ProductsModel {
    private $mysqli; 

    public function __construct() {
        $database = new Database();
        $this->mysqli = $database->getConnection(); 
    }

    // Función para mostrar los últimos productos
    public function getLatestProducts($limit = 8) {
        try {
            $sql = "SELECT id_producto, nombre_producto, precio, ruta_imagen 
                    FROM producto 
                    ORDER BY id_producto DESC 
                    LIMIT ?";
            
            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->mysqli->error);
            }

            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {
            die('Error en getLatestProducts: ' . $e->getMessage());
        }
    }

    // Función para mostrar los productos más vendidos
    public function getBestSellingProducts($limit = 4) {
        try {
            $sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.ruta_imagen, SUM(dp.cantidad) as total_vendido
                    FROM producto p
                    LEFT JOIN detalle_pedido dp ON p.id_producto = dp.id_producto
                    GROUP BY p.id_producto, p.nombre_producto, p.precio, p.ruta_imagen
                    ORDER BY total_vendido DESC
                    LIMIT ?";
            
            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Error al preparar la consulta de más vendidos: ' . $this->mysqli->error);
            }

            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {
            die('Error en getBestSellingProducts: ' . $e->getMessage());
        }
    }
}