<?php

// 1. Incluimos la conexión.
require_once __DIR__ . '/../../config/conexion.php';

// 2. Definimos la clase del controlador.
class ProductController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Obtiene los productos filtrando por el nombre de la categoría.
     * Esta es la versión final y correcta.
     */
    public function getProductsByCategory($categoryName) {
        
        // ¡CONSULTA FINAL Y CORRECTA!
        // Busca en la tabla 'producto' y filtra por la columna 'categoria'.
        $sql = "SELECT * FROM producto WHERE categoria = :categoryName";

        try {
            $stmt = $this->db->prepare($sql);
            
            // Vinculamos el nombre de la categoría que queremos buscar.
            $stmt->bindParam(':categoryName', $categoryName); 
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Si algo falla, lo sabremos.
            die("ERROR EN LA CONSULTA: " . $e->getMessage());
        }
    }
}
?>