<?php
// controllers/admin/obtenerProducto.php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin' || !isset($_GET['id'])) {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado o ID no proporcionado.']);
    exit;
}

$id_producto = intval($_GET['id']);
$conexion = new mysqli("localhost", "root", "", "shopnexs");
$stmt = $conexion->prepare("SELECT nombre_producto, descripcion, precio, stock FROM producto WHERE id_producto = ?");
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($producto = $resultado->fetch_assoc()) {
    echo json_encode(['success' => true, 'producto' => $producto]);
} else {
    echo json_encode(['success' => false, 'error' => 'Producto no encontrado.']);
}
$stmt->close();
$conexion->close();
?>