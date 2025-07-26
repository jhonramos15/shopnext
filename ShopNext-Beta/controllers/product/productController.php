<?php
// controllers/product/productController.php

// ¡ESTA ES LA RUTA CORREGIDA!
// __DIR__ -> /controllers/product
// ../../ -> / (la raíz del proyecto)
// Ahora encuentra /config/conexion.php
require_once __DIR__ . '/../../config/conexion.php';

class ProductController {
    private $conn;

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->conectar();
    }

    public function getAllProducts() {
        $query = "SELECT * FROM producto";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $this->conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        
        $stmt->close();
        return $products;
    }

    public function getProductsByCategory($categoryName) {
        $stmt = $this->conn->prepare("SELECT * FROM producto WHERE categoria = ?");
        
        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        
        $stmt->close();
        return $products;
    }
}
?>