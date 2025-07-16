<?php
// controllers/admin/accionesTicket.php
header('Content-Type: application/json');
session_start();

// Guardián de seguridad
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
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
$id_ticket = isset($_POST['id_ticket']) ? intval($_POST['id_ticket']) : 0;
$accion = $_POST['accion'] ?? '';

if ($id_ticket > 0 && $accion === 'resolver') {
    // Preparamos la consulta para actualizar el estado a 'Resuelto'
    $stmt = $conexion->prepare("UPDATE tickets SET estado = 'Resuelto' WHERE id_ticket = ?");
    $stmt->bind_param("i", $id_ticket);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'El ticket no se encontró o ya estaba resuelto.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar la base de datos.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos o acción no reconocida.']);
}

$conexion->close();
?>