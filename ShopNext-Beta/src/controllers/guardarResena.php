<?php
session_start();
header('Content-Type: application/json');

// 1. VERIFICAR SI EL USUARIO ESTÁ AUTENTICADO
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401); // No autorizado
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para dejar una reseña.']);
    exit;
}

// 2. CONEXIÓN A LA BASE DE DATOS
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión con el servidor.']);
    exit;
}
$conexion->set_charset("utf8mb4");

// 3. OBTENER ID y NOMBRE DEL CLIENTE DESDE LA BD (MÁS SEGURO)
$id_usuario_actual = $_SESSION['id_usuario'];
$id_cliente = null;
$nombre_usuario = null;

$stmt_cliente = $conexion->prepare("SELECT id_cliente, nombre FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario_actual);
$stmt_cliente->execute();
$resultado_cliente = $stmt_cliente->get_result();

if ($fila_cliente = $resultado_cliente->fetch_assoc()) {
    $id_cliente = $fila_cliente['id_cliente'];
    $nombre_usuario = $fila_cliente['nombre'];
}
$stmt_cliente->close();

// Si no se encontró un cliente asociado, es un error.
if (is_null($id_cliente)) {
    http_response_code(403); // Prohibido
    echo json_encode(['success' => false, 'message' => 'Tu cuenta no está asociada a un perfil de cliente.']);
    exit;
}

// 4. VALIDAR LOS DATOS RECIBIDOS DEL FORMULARIO
$id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
$puntuacion = filter_input(INPUT_POST, 'puntuacion', FILTER_VALIDATE_INT);
$comentario = trim(filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING));

if (!$id_producto || !$puntuacion) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['success' => false, 'message' => 'Datos incompletos. Por favor, selecciona una puntuación.']);
    exit;
}

// 5. INSERTAR LA RESEÑA CON EL ID_CLIENTE
$sql = "INSERT INTO resenas (id_producto, id_cliente, nombre_usuario, puntuacion, comentario) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iisis", $id_producto, $id_cliente, $nombre_usuario, $puntuacion, $comentario);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '¡Gracias por tu reseña!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar la reseña. Inténtalo más tarde.']);
}

$stmt->close();
$conexion->close();
?>