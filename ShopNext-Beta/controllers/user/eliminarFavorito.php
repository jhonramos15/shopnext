<?php
session_start();
header('Content-Type: application/json'); // La respuesta siempre será JSON

// --- Validaciones de Seguridad ---

// 1. Debe ser una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}

// 2. El usuario debe estar logueado como cliente
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    http_response_code(401); // No autorizado
    echo json_encode(['success' => false, 'error' => 'Debes iniciar sesión para realizar esta acción.']);
    exit;
}

// 3. Debemos recibir el ID del producto
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id_producto']) || !filter_var($data['id_producto'], FILTER_VALIDATE_INT)) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['success' => false, 'error' => 'ID de producto no válido.']);
    exit;
}

$id_producto_a_eliminar = $data['id_producto'];
$id_usuario_session = $_SESSION['id_usuario'];

// --- Lógica de la Base de Datos ---
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos.']);
    exit;
}

try {
    // Primero, obtenemos el id_cliente asociado al usuario logueado
    $stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
    $stmt_cliente->bind_param("i", $id_usuario_session);
    $stmt_cliente->execute();
    $resultado_cliente = $stmt_cliente->get_result();

    if ($resultado_cliente->num_rows === 0) {
        throw new Exception("Cliente no encontrado.");
    }
    $id_cliente = $resultado_cliente->fetch_assoc()['id_cliente'];
    $stmt_cliente->close();

    // Ahora sí, eliminamos el favorito que coincida con el cliente y el producto
    $stmt_delete = $conexion->prepare("DELETE FROM lista_favoritos WHERE id_cliente = ? AND id_producto = ?");
    $stmt_delete->bind_param("ii", $id_cliente, $id_producto_a_eliminar);
    $stmt_delete->execute();

    if ($stmt_delete->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado de favoritos.']);
    } else {
        throw new Exception("El producto no se encontraba en tu lista o ya fue eliminado.");
    }

    $stmt_delete->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    $conexion->close();
}
?>