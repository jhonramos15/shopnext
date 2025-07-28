<?php
session_start();
header('Content-Type: application/json');

// 1. Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401); // No autorizado
    echo json_encode(['success' => false, 'error' => 'login_required', 'message' => 'Debes iniciar sesión para añadir productos al carrito.']);
    exit;
}

// 2. Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión con el servidor.']);
    exit;
}

// 3. Obtener el ID del cliente y OBTENER/CREAR su carrito
$id_usuario = $_SESSION['id_usuario'];
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario);
$stmt_cliente->execute();
$id_cliente = $stmt_cliente->get_result()->fetch_assoc()['id_cliente'];
$stmt_cliente->close();

if (!$id_cliente) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Usuario no válido.']);
    exit;
}

// Buscar el carrito del cliente o crearlo si no existe
$stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
$stmt_carrito->bind_param("i", $id_cliente);
$stmt_carrito->execute();
$carrito_res = $stmt_carrito->get_result();

if ($carrito_res->num_rows > 0) {
    $id_carrito = $carrito_res->fetch_assoc()['id_carrito'];
} else {
    // Si no tiene carrito, se lo creamos
    $stmt_create_cart = $conexion->prepare("INSERT INTO carrito (id_cliente) VALUES (?)");
    $stmt_create_cart->bind_param("i", $id_cliente);
    $stmt_create_cart->execute();
    $id_carrito = $conexion->insert_id;
    $stmt_create_cart->close();
}
$stmt_carrito->close();

// 4. Procesar la acción solicitada
$action = $_POST['action'] ?? 'agregar'; // 'agregar' es la acción por defecto si no se especifica

switch ($action) {
    case 'agregar':
        $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

        if ($id_producto > 0 && $cantidad > 0) {
            // Verificar si el producto ya está en el carrito para actualizar la cantidad
            $stmt_check = $conexion->prepare("SELECT id_producto_carrito, cantidad FROM producto_carrito WHERE id_carrito = ? AND id_producto = ?");
            $stmt_check->bind_param("ii", $id_carrito, $id_producto);
            $stmt_check->execute();
            $check_res = $stmt_check->get_result();

            if ($check_res->num_rows > 0) {
                // El producto ya existe, actualizamos la cantidad
                $item_existente = $check_res->fetch_assoc();
                $nueva_cantidad = $item_existente['cantidad'] + $cantidad;
                $stmt_update = $conexion->prepare("UPDATE producto_carrito SET cantidad = ? WHERE id_producto_carrito = ?");
                $stmt_update->bind_param("ii", $nueva_cantidad, $item_existente['id_producto_carrito']);
                $stmt_update->execute();
                $stmt_update->close();
            } else {
                // El producto es nuevo, lo insertamos
                $stmt_insert = $conexion->prepare("INSERT INTO producto_carrito (id_carrito, id_producto, cantidad) VALUES (?, ?, ?)");
                $stmt_insert->bind_param("iii", $id_carrito, $id_producto, $cantidad);
                $stmt_insert->execute();
                $stmt_insert->close();
            }
            echo json_encode(['success' => true, 'message' => 'Producto añadido al carrito.']);
            $stmt_check->close();
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos de producto inválidos.']);
        }
        break;

    case 'eliminar':
        $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
        if ($id_producto > 0) {
            $stmt_delete = $conexion->prepare("DELETE FROM producto_carrito WHERE id_carrito = ? AND id_producto = ?");
            $stmt_delete->bind_param("ii", $id_carrito, $id_producto);
            $stmt_delete->execute();
            echo json_encode(['success' => true, 'message' => 'Producto eliminado.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de producto inválido.']);
        }
        break;

    case 'vaciar':
        $stmt_clear = $conexion->prepare("DELETE FROM producto_carrito WHERE id_carrito = ?");
        $stmt_clear->bind_param("i", $id_carrito);
        $stmt_clear->execute();
        echo json_encode(['success' => true, 'message' => 'Carrito vaciado.']);
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida.']);
        break;
}

$conexion->close();
?>