<?php
// controllers/cart/carrito_api.php
header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$action = $_POST['action'] ?? '';
$id_producto_carrito = isset($_POST['id_producto_carrito']) ? intval($_POST['id_producto_carrito']) : 0;

if ($id_producto_carrito === 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
    exit;
}

switch ($action) {
    case 'update':
        $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
        if ($cantidad < 1) $cantidad = 1;

        $stmt = $conexion->prepare("UPDATE producto_carrito SET cantidad = ? WHERE id_producto_carrito = ?");
        $stmt->bind_param("ii", $cantidad, $id_producto_carrito);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Update failed']);
        }
        $stmt->close();
        break;

    case 'delete':
        $stmt = $conexion->prepare("DELETE FROM producto_carrito WHERE id_producto_carrito = ?");
        $stmt->bind_param("i", $id_producto_carrito);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Delete failed']);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}

$conexion->close();
?>