<?php
// controllers/accionesProducto.php

session_start();
$conexion = new mysqli("localhost", "root", "", "shopnexs");

// Guardián de seguridad: Asegura que solo un vendedor logueado pueda hacer cambios.
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    http_response_code(403); // Código de "Prohibido"
    echo json_encode(['error' => 'Acceso no autorizado.']);
    exit;
}

// Obtenemos el id_vendedor para asegurarnos de que solo modifique SUS productos.
$id_usuario_session = $_SESSION['id_usuario'];
$stmt_vendedor = $conexion->prepare("SELECT id_vendedor FROM vendedor WHERE id_usuario = ?");
$stmt_vendedor->bind_param("i", $id_usuario_session);
$stmt_vendedor->execute();
$vendedor = $stmt_vendedor->get_result()->fetch_assoc();
$id_vendedor_seguro = $vendedor['id_vendedor'];
$stmt_vendedor->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // --- LÓGICA PARA ELIMINAR UN PRODUCTO ---
    if ($accion === 'eliminar') {
        $id_producto = intval($_POST['id_producto']);
        // La consulta incluye el id_vendedor para máxima seguridad.
        $stmt = $conexion->prepare("DELETE FROM producto WHERE id_producto = ? AND id_vendedor = ?");
        $stmt->bind_param("ii", $id_producto, $id_vendedor_seguro);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['success' => 'Producto eliminado correctamente.']);
        } else {
            echo json_encode(['error' => 'No se pudo eliminar el producto o no tienes permiso.']);
        }
    }

    // --- LÓGICA PARA EDITAR UN PRODUCTO ---
    if ($accion === 'editar') {
        $id_producto = intval($_POST['id_producto']);
        $nombre = trim($_POST['nombre_producto']);
        $categoria = trim($_POST['categoria']);
        $precio = floatval($_POST['precio']);
        $stock = intval($_POST['stock']);

        $stmt = $conexion->prepare("UPDATE producto SET nombre_producto = ?, categoria = ?, precio = ?, stock = ? WHERE id_producto = ? AND id_vendedor = ?");
        $stmt->bind_param("ssdiis", $nombre, $categoria, $precio, $stock, $id_producto, $id_vendedor_seguro);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Producto actualizado.']);
        } else {
            echo json_encode(['error' => 'No se pudo actualizar el producto.']);
        }
    }
}
?>