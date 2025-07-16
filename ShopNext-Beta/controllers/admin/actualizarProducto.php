<?php
// controllers/admin/actualizarProducto.php
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado.']);
    exit;
}

$id_producto = intval($_POST['id_producto']);
$nombre = $_POST['nombre_producto'];
$descripcion = $_POST['descripcion'];
$precio = floatval($_POST['precio']);
$stock = intval($_POST['stock']);

$conexion = new mysqli("localhost", "root", "", "shopnexs");
$stmt = $conexion->prepare("UPDATE producto SET nombre_producto = ?, descripcion = ?, precio = ?, stock = ? WHERE id_producto = ?");
$stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id_producto);

if ($stmt->execute()) {
    // Devolvemos el producto actualizado para refrescar la tabla en vivo
    $updatedProduct = [
        'id_producto' => $id_producto,
        'nombre_producto' => $nombre,
        'precio' => $precio,
        'stock' => $stock
    ];
    echo json_encode(['success' => true, 'producto' => $updatedProduct]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar el producto.']);
}
$stmt->close();
$conexion->close();
?>