<?php
// En src/models/ProductsModel.php

namespace App\Models;

use Config\Database;

class ProductsModel {

    private $conn;

    public function __construct() {
        $database = new \Config\Database(); 
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            die("<h1>Error Crítico</h1><p>ProductsModel no pudo obtener la conexión de la clase Database.</p>");
        }
    }

    /**
     * Obtiene los productos más vendidos.
     */
    public function getBestSellingProducts(int $limit): array
    {
        $sql = "
            SELECT p.*, SUM(dp.cantidad) as total_vendido
            FROM detalle_pedido dp
            JOIN producto p ON dp.id_producto = p.id_producto
            GROUP BY p.id_producto
            ORDER BY total_vendido DESC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene los últimos productos añadidos.
     */
    public function getLatestProducts(int $limit): array {
        $sql = "SELECT id_producto, nombre_producto, precio, ruta_imagen FROM producto ORDER BY id_producto DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene la reseña promedio y el total de reseñas.
     */
    public function getResenaPromedioPorProducto(int $id_producto): array {
        $sql = "SELECT AVG(puntuacion) as promedio, COUNT(id_resena) as total FROM resenas WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        
        return [
            'promedio' => $resultado['promedio'] ?? 0,
            'total' => $resultado['total'] ?? 0
        ];
    }

    /**
     * Busca un producto por su ID y recupera todos sus datos.
     * (Función completamente corregida)
     */
    public function findProductById($id) {
        // 1. Obtener los datos principales del producto y su categoría
        // Se usa LEFT JOIN para que los productos sin categoría no causen un error.
        $sql = "SELECT p.*, c.nombre AS nombre_categoria 
                FROM producto p
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                WHERE p.id_producto = ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta para buscar producto: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $producto = $stmt->get_result()->fetch_assoc();

        if (!$producto) {
            return null;
        }

        // 2. Obtener imágenes
        // CORRECCIÓN: La tabla 'imagenes_producto' no existe.
        // En su lugar, creamos un array con la imagen principal del producto para mantener una estructura de datos consistente.
        $producto['imagenes'] = [];
        if (!empty($producto['ruta_imagen'])) {
            $producto['imagenes'][] = ['ruta_imagen' => $producto['ruta_imagen']];
        }

        // 3. Obtener reseñas
        // CORRECCIÓN: La columna se llama 'fecha_creacion', no 'fecha_resena'.
        // Y el nombre del usuario ya está en la tabla 'resenas', por lo que no se necesita el JOIN a 'usuario'.
        $sql_reviews = "SELECT *
                        FROM resenas
                        WHERE id_producto = ? 
                        ORDER BY fecha_creacion DESC";
        
        $stmt_reviews = $this->conn->prepare($sql_reviews);
        if (!$stmt_reviews) {
            error_log("Error al preparar la consulta de reseñas: " . $this->conn->error);
            $producto['reseñas'] = []; // Asignar un array vacío si la consulta falla
        } else {
            $stmt_reviews->bind_param("i", $id);
            $stmt_reviews->execute();
            $producto['reseñas'] = $stmt_reviews->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        // 4. Calcular promedio de reseñas
        $totalResenas = count($producto['reseñas']);
        $sumaCalificaciones = 0;
        foreach ($producto['reseñas'] as $resena) {
            $sumaCalificaciones += $resena['puntuacion'];
        }
        $promedio = ($totalResenas > 0) ? $sumaCalificaciones / $totalResenas : 0;
        
        $producto['resena_promedio'] = round($promedio, 1);
        $producto['resena_total'] = $totalResenas;
        
        return $producto;
    }

    public function searchByName(string $searchTerm): array {
        // La consulta busca en el nombre Y en la descripción para mejores resultados
        $sql = "SELECT id_producto, nombre_producto, precio, ruta_imagen 
                FROM producto 
                WHERE nombre_producto LIKE ? OR descripcion LIKE ?";
        
        $stmt = $this->conn->prepare($sql);
        
        // Añadimos los comodines '%' para que la búsqueda sea flexible
        $likeTerm = '%' . $searchTerm . '%';
        // Usamos el mismo término para ambos parámetros
        $stmt->bind_param("ss", $likeTerm, $likeTerm);
        
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>