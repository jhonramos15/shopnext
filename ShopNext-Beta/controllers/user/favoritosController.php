<?php
// controllers/user/favoritosController.php

session_start();
header('Content-Type: application/json');

// 1. Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    echo json_encode(['success' => false, 'error' => 'login_required']);
    exit;
}

if (!isset($_POST['id_producto'])) {
    echo json_encode(['success' => false, 'error' => 'Producto no especificado.']);
    exit;
}

$id_producto = filter_var($_POST['id_producto'], FILTER_VALIDATE_INT);
$id_usuario = $_SESSION['id_usuario'];
$response = [];

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Error de conexión.']);
    exit;
}

try {
    // 2. Obtener el id_cliente a partir del id_usuario
    $stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
    $stmt_cliente->bind_param("i", $id_usuario);
    $stmt_cliente->execute();
    $resultado_cliente = $stmt_cliente->get_result();
    
    if ($resultado_cliente->num_rows === 0) {
        throw new Exception("Perfil de cliente no encontrado.");
    }
    $id_cliente = $resultado_cliente->fetch_assoc()['id_cliente'];
    $stmt_cliente->close();

    // 3. Revisar si el producto ya está en favoritos
    $stmt_check = $conexion->prepare("SELECT id_favorito FROM lista_favoritos WHERE id_cliente = ? AND id_producto = ?");
    $stmt_check->bind_param("ii", $id_cliente, $id_producto);
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();
    $existe = $resultado_check->fetch_assoc();
    $stmt_check->close();

    if ($existe) {
        // Si ya existe, lo eliminamos
        $stmt_delete = $conexion->prepare("DELETE FROM lista_favoritos WHERE id_favorito = ?");
        $stmt_delete->bind_param("i", $existe['id_favorito']);
        $stmt_delete->execute();
        $stmt_delete->close();
        $response = ['success' => true, 'action' => 'removed'];
    } else {
        // Si no existe, lo insertamos
        $stmt_add = $conexion->prepare("INSERT INTO lista_favoritos (id_cliente, id_producto) VALUES (?, ?)");
        $stmt_add->bind_param("ii", $id_cliente, $id_producto);
        $stmt_add->execute();
        $stmt_add->close();
        $response = ['success' => true, 'action' => 'added'];
    }

} catch (Exception $e) {
    $response = ['success' => false, 'error' => $e->getMessage()];
}

$conexion->close();
echo json_encode($response);
exit;