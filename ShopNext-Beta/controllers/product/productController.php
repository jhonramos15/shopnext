<?php
// Incluimos la conexión a la base de datos.
require_once __DIR__ . '/../../config/conexion.php';

class ProductController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Obtiene los productos filtrando por el nombre de la categoría.
     */
    public function getProductsByCategory($categoryName) {
        $query = "SELECT 
                    id_producto, 
                    nombre_producto, 
                    descripcion, 
                    precio, 
                    stock, 
                    ruta_imagen 
                  FROM 
                    producto
                  WHERE 
                    categoria = :category_name AND stock > 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_name', $categoryName);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>