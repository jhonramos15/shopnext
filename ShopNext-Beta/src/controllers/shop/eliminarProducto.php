<?php
session_start();
header('Content-Type: application/json'); // Indicamos que la respuesta será en formato JSON

// --- Validaciones iniciales ---
// 1. Asegurarse de que el método sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// 2. Asegurarse de que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401); // No autorizado
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para realizar esta acción.']);
    exit;
}

// 3. Asegurarse de que se envió el id_producto
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id_producto'])) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['success' => false, 'message' => 'ID del producto no proporcionado.']);
    exit;
}

// --- Lógica de la base de datos ---
$id_producto_a_eliminar = $data['id_producto'];
$id_usuario_session = $_SESSION['id_usuario'];

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500); // Error interno del servidor
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    exit;
}

// Primero, obtenemos el id_cliente asociado al usuario logueado
$id_cliente = null;
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario_session);
$stmt_cliente->execute();
$resultado_cliente = $stmt_cliente->get_result();

if ($fila = $resultado_cliente->fetch_assoc()) {
    $id_cliente = $fila['id_cliente'];
}
$stmt_cliente->close();

if ($id_cliente === null) {
    http_response_code(403); // Prohibido
    echo json_encode(['success' => false, 'message' => 'No se encontró un cliente asociado a tu cuenta.']);
    $conexion->close();
    exit;
}

// Ahora sí, eliminamos el favorito de la base de datos
$sql_delete = "DELETE FROM lista_favoritos WHERE id_cliente = ? AND id_producto = ?";
$stmt_delete = $conexion->prepare($sql_delete);
$stmt_delete->bind_param("ii", $id_cliente, $id_producto_a_eliminar);

if ($stmt_delete->execute()) {
    if ($stmt_delete->affected_rows > 0) {
        // Si se eliminó al menos una fila, fue un éxito
        echo json_encode(['success' => true, 'message' => 'Producto eliminado de favoritos.']);
    } else {
        // Si no se afectaron filas, es porque el producto ya no estaba en la lista
        echo json_encode(['success' => false, 'message' => 'El producto no estaba en tu lista de favoritos.']);
    }
} else {
    // Si hubo un error en la consulta
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al intentar eliminar el producto.']);
}

$stmt_delete->close();
$conexion->close();
?>