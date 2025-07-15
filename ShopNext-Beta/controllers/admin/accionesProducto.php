<?php
// controllers/admin/accionesProducto.php
header('Content-Type: application/json');

session_start();

// Doble guardián: verifica que sea una petición POST y que el usuario sea admin
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'error' => 'Acceso denegado.']);
    exit;
}

// Obtenemos el ID del producto del cuerpo de la petición
$data = json_decode(file_get_contents('php://input'), true);
$id_producto = isset($data['id_producto']) ? intval($data['id_producto']) : 0;

if ($id_producto === 0) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'error' => 'ID de producto no válido.']);
    exit;
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos.']);
    exit;
}

// --- Lógica para eliminar el producto ---
// Primero, eliminamos las referencias en `producto_carrito` para evitar errores de clave foránea
$stmt_carrito = $conexion->prepare("DELETE FROM producto_carrito WHERE id_producto = ?");
$stmt_carrito->bind_param("i", $id_producto);
$stmt_carrito->execute();
$stmt_carrito->close();

// Ahora, eliminamos el producto de la tabla principal
$stmt_producto = $conexion->prepare("DELETE FROM producto WHERE id_producto = ?");
$stmt_producto->bind_param("i", $id_producto);

if ($stmt_producto->execute()) {
    if ($stmt_producto->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'El producto no fue encontrado o ya había sido eliminado.']);
    }
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al ejecutar la consulta de eliminación.']);
}

$stmt_producto->close();
$conexion->close();
?>