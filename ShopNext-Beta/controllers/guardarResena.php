<?php
header('Content-Type: application/json');

// Conexión a la base de datos (usa la misma que en searchController.php)
$conexion = new mysqli("localhost", "root", "", "shopnexs");

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit;
}

// Validamos que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// Obtenemos los datos del formulario
$id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
$nombre_usuario = trim(filter_input(INPUT_POST, 'nombre_usuario', FILTER_SANITIZE_STRING));
$puntuacion = filter_input(INPUT_POST, 'puntuacion', FILTER_VALIDATE_INT);
$comentario = trim(filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING));

// Validaciones básicas
if (!$id_producto || !$nombre_usuario || !$puntuacion) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios (producto, nombre o puntuación).']);
    exit;
}

if ($puntuacion < 1 || $puntuacion > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'La puntuación debe estar entre 1 y 5.']);
    exit;
}

// Preparamos la consulta para insertar la reseña de forma segura
$sql = "INSERT INTO resenas (id_producto, nombre_usuario, puntuacion, comentario) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    exit;
}

$stmt->bind_param("isis", $id_producto, $nombre_usuario, $puntuacion, $comentario);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '¡Gracias por tu reseña!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar tu reseña. Inténtalo de nuevo.']);
}

$stmt->close();
$conexion->close();
?>