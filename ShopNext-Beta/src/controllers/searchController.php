<?php
// controllers/searchController.php (VERSIÓN FINAL ANTI-ESPACIOS)

header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "shopnexs");

if ($conexion->connect_error) {
    echo json_encode(['error' => 'Falló la conexión: ' . $conexion->connect_error]);
    exit;
}

$conexion->set_charset("utf8mb4");

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];

if (strlen($query) >= 2) { // Puedes ajustar este número, 2 o 3 es un buen valor.
    $search_term = "%" . strtolower($query) . "%";

    // LA CONSULTA MÁS ROBUSTA POSIBLE
    // Usamos TRIM() para quitar espacios y LOWER() para mayúsculas.
    $sql = "SELECT id_producto, nombre_producto, precio, ruta_imagen 
            FROM producto 
            WHERE (
                LOWER(TRIM(nombre_producto)) LIKE ? OR 
                LOWER(TRIM(descripcion)) LIKE ? OR 
                LOWER(TRIM(categoria)) LIKE ?
            ) AND stock > 0
            LIMIT 5";

    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sss", $search_term, $search_term, $search_term);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $results[] = $fila;
            }
        }
        $stmt->close();
    }
}

$conexion->close();
echo json_encode($results);
?>