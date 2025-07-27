<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "shopnexs");

// Guardián de seguridad
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Obtener el id_vendedor del usuario de la sesión
$id_usuario_session = $_SESSION['id_usuario'];
$stmt_vendedor = $conexion->prepare("SELECT id_vendedor FROM vendedor WHERE id_usuario = ?");
$stmt_vendedor->bind_param("i", $id_usuario_session);
$stmt_vendedor->execute();
$vendedor = $stmt_vendedor->get_result()->fetch_assoc();
$id_vendedor = $vendedor['id_vendedor'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // --- LÓGICA PARA ELIMINAR ---
    if ($accion === 'eliminar') {
        $id_producto = intval($_POST['id_producto']);
        // La consulta INCLUYE el id_vendedor para seguridad
        $stmt = $conexion->prepare("DELETE FROM producto WHERE id_producto = ? AND id_vendedor = ?");
        $stmt->bind_param("ii", $id_producto, $id_vendedor);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['success' => 'Producto eliminado']);
        } else {
            echo json_encode(['error' => 'No se pudo eliminar el producto']);
        }
    }

    // --- LÓGICA PARA EDITAR ---
    if ($accion === 'editar') {
        $id_producto = intval($_POST['id_producto']);
        $nombre = $_POST['nombre_producto'];
        $categoria = $_POST['categoria'];
        $precio = floatval($_POST['precio']);
        $stock = intval($_POST['stock']);

        $stmt = $conexion->prepare("UPDATE producto SET nombre_producto = ?, categoria = ?, precio = ?, stock = ? WHERE id_producto = ? AND id_vendedor = ?");
        $stmt->bind_param("ssdiis", $nombre, $categoria, $precio, $stock, $id_producto, $id_vendedor);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Producto actualizado']);
        } else {
            echo json_encode(['error' => 'No se pudo actualizar el producto']);
        }
    }
}
?>