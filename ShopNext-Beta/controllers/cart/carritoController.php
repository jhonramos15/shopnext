<?php
// controllers/carritoController.php
session_start();

// Guardián: Si no es un cliente, lo redirigimos con una alerta
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: ../views/auth/login.php?error=login_required");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Error de conexión"); }

// Obtenemos el id_cliente a partir del id_usuario de la sesión
$id_usuario_actual = $_SESSION['id_usuario'];
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario_actual);
$stmt_cliente->execute();
$resultado_cliente = $stmt_cliente->get_result();

if ($resultado_cliente->num_rows === 0) {
    die("Error: Perfil de cliente no encontrado para este usuario.");
}
$id_cliente = $resultado_cliente->fetch_assoc()['id_cliente'];
$stmt_cliente->close();

// --- Lógica para Añadir al Carrito ---

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);

    // 1. Buscamos o creamos el carrito principal del cliente
    $stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
    $stmt_carrito->bind_param("i", $id_cliente);
    $stmt_carrito->execute();
    $resultado_carrito = $stmt_carrito->get_result();
    
    if ($resultado_carrito->num_rows === 0) {
        $stmt_crear = $conexion->prepare("INSERT INTO carrito (id_cliente) VALUES (?)");
        $stmt_crear->bind_param("i", $id_cliente);
        $stmt_crear->execute();
        $id_carrito = $conexion->insert_id;
    } else {
        $id_carrito = $resultado_carrito->fetch_assoc()['id_carrito'];
    }
    $stmt_carrito->close();

    // 2. Verificamos si el producto ya está en el carrito para aumentar la cantidad
    $stmt_check = $conexion->prepare("SELECT id_producto_carrito FROM producto_carrito WHERE id_carrito = ? AND id_producto = ?");
    $stmt_check->bind_param("ii", $id_carrito, $id_producto);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows > 0) {
        $stmt_update = $conexion->prepare("UPDATE producto_carrito SET cantidad = cantidad + 1 WHERE id_carrito = ? AND id_producto = ?");
        $stmt_update->bind_param("ii", $id_carrito, $id_producto);
        $stmt_update->execute();
    } else {
        $stmt_insert = $conexion->prepare("INSERT INTO producto_carrito (id_carrito, id_producto) VALUES (?, ?)");
        $stmt_insert->bind_param("ii", $id_carrito, $id_producto);
        $stmt_insert->execute();
    }
    
    // 3. Redirigimos de vuelta con un mensaje de éxito
    header("Location: ../../public/index.php?status=added_to_cart");
    exit;
}
?>