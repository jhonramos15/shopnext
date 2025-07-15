<?php
// controllers/cart/carritoController.php
session_start();
// Le decimos al navegador que la respuesta será en formato JSON
header('Content-Type: application/json');

// ¡Guardián! Si no está logueado, devuelve un error JSON.
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    echo json_encode(['error' => 'login_required']);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    // Enviamos un código de error de servidor
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos.']);
    exit;
}

// Obtenemos el id_cliente
$id_usuario = $_SESSION['id_usuario'];
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario);
$stmt_cliente->execute();
$cliente = $stmt_cliente->get_result()->fetch_assoc();

if (!$cliente) {
    http_response_code(404);
    echo json_encode(['error' => 'No se encontró un perfil de cliente para este usuario.']);
    exit;
}
$id_cliente = $cliente['id_cliente'];
$stmt_cliente->close();

// Lógica para añadir al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);
    
    // 1. Buscamos o creamos el carrito principal
    $stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
    $stmt_carrito->bind_param("i", $id_cliente);
    $stmt_carrito->execute();
    $resultado_carrito = $stmt_carrito->get_result();
    
    $id_carrito;
    if ($resultado_carrito->num_rows === 0) {
        $stmt_crear = $conexion->prepare("INSERT INTO carrito (id_cliente, fecha_creacion) VALUES (?, NOW())");
        $stmt_crear->bind_param("i", $id_cliente);
        $stmt_crear->execute();
        $id_carrito = $conexion->insert_id;
        $stmt_crear->close();
    } else {
        $id_carrito = $resultado_carrito->fetch_assoc()['id_carrito'];
    }
    $stmt_carrito->close();
    
    // 2. Añadimos o actualizamos el producto en la tabla `producto_carrito`
    $stmt_check = $conexion->prepare("SELECT id_producto_carrito FROM producto_carrito WHERE id_carrito = ? AND id_producto = ?");
    $stmt_check->bind_param("ii", $id_carrito, $id_producto);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows > 0) {
        // Si el producto ya está, aumentamos la cantidad
        $stmt_update = $conexion->prepare("UPDATE producto_carrito SET cantidad = cantidad + 1 WHERE id_carrito = ? AND id_producto = ?");
        $stmt_update->bind_param("ii", $id_carrito, $id_producto);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Si no está, lo insertamos
        $stmt_insert = $conexion->prepare("INSERT INTO producto_carrito (id_carrito, id_producto, cantidad) VALUES (?, ?, 1)");
        $stmt_insert->bind_param("ii", $id_carrito, $id_producto);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    $stmt_check->close();
    
    // Enviamos una respuesta de éxito en JSON.
    echo json_encode(['success' => 'Producto añadido correctamente.']);
    exit;
}

// Si la solicitud no es válida, enviamos un error.
http_response_code(400); // Código para "Bad Request"
echo json_encode(['error' => 'Solicitud inválida.']);
$conexion->close();

?>