<?php
// controllers/vendedor/accionesPedido.php
header('Content-Type: application/json');
session_start();

// Guardián de seguridad: solo peticiones POST y solo de vendedores.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acceso no autorizado.']);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos.']);
    exit;
}

// Obtenemos los datos enviados por JavaScript
$id_pedido = isset($_POST['id_pedido']) ? intval($_POST['id_pedido']) : 0;
$nuevo_estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';

// Validamos que los datos sean correctos
$estados_permitidos = ['pendiente', 'procesado', 'enviado', 'entregado', 'cancelado'];
if ($id_pedido === 0 || !in_array($nuevo_estado, $estados_permitidos)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos para la actualización.']);
    exit;
}

// Preparamos la consulta para actualizar el estado del pedido
$stmt = $conexion->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
$stmt->bind_param("si", $nuevo_estado, $id_pedido);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Estado del pedido actualizado.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'El pedido no se encontró o ya tenía ese estado.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar la base de datos.']);
}

$stmt->close();
$conexion->close();
?>