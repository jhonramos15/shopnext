<?php
session_start();
header('Content-Type: application/json');

// Guardián para asegurar que solo un cliente logueado pueda ejecutar esto
require_once __DIR__ . '/../../controllers/authGuardCliente.php';

$response = ['success' => false, 'message' => 'Un error inesperado ocurrió.'];

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    $response['message'] = 'Error de conexión a la base de datos.';
    echo json_encode($response);
    exit;
}

$id_usuario_session = $_SESSION['id_usuario'];
$id_cliente = null;
$id_carrito = null;

// Iniciamos una transacción para asegurar que todas las operaciones se completen con éxito
$conexion->begin_transaction();

try {
    // 1. OBTENER ID DEL CLIENTE
    $stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
    $stmt_cliente->bind_param("i", $id_usuario_session);
    $stmt_cliente->execute();
    $resultado_cliente = $stmt_cliente->get_result();
    if ($fila = $resultado_cliente->fetch_assoc()) {
        $id_cliente = $fila['id_cliente'];
    }
    $stmt_cliente->close();

    if ($id_cliente === null) {
        throw new Exception("Perfil de cliente no encontrado.");
    }

    // 2. OBTENER LA LISTA DE FAVORITOS
    $stmt_favoritos = $conexion->prepare("SELECT id_producto FROM lista_favoritos WHERE id_cliente = ?");
    $stmt_favoritos->bind_param("i", $id_cliente);
    $stmt_favoritos->execute();
    $favoritos = $stmt_favoritos->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_favoritos->close();

    if (empty($favoritos)) {
        throw new Exception("Tu lista de deseos está vacía.");
    }

    // 3. OBTENER O CREAR EL CARRITO DEL USUARIO
    $stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
    $stmt_carrito->bind_param("i", $id_cliente);
    $stmt_carrito->execute();
    $resultado_carrito = $stmt_carrito->get_result();
    if ($fila = $resultado_carrito->fetch_assoc()) {
        $id_carrito = $fila['id_carrito'];
    } else {
        $stmt_crear_carrito = $conexion->prepare("INSERT INTO carrito (id_cliente) VALUES (?)");
        $stmt_crear_carrito->bind_param("i", $id_cliente);
        $stmt_crear_carrito->execute();
        $id_carrito = $conexion->insert_id;
        $stmt_crear_carrito->close();
    }
    $stmt_carrito->close();

    // 4. MOVER CADA PRODUCTO FAVORITO AL CARRITO
    foreach ($favoritos as $favorito) {
        $id_producto = $favorito['id_producto'];

        // Revisamos si el producto ya existe en el carrito para solo sumar la cantidad
        $stmt_check = $conexion->prepare("SELECT cantidad FROM producto_carrito WHERE id_carrito = ? AND id_producto = ?");
        $stmt_check->bind_param("ii", $id_carrito, $id_producto);
        $stmt_check->execute();
        $producto_en_carrito = $stmt_check->get_result()->fetch_assoc();
        $stmt_check->close();

        if ($producto_en_carrito) {
            // Si ya está, actualizamos la cantidad
            $nueva_cantidad = $producto_en_carrito['cantidad'] + 1;
            $stmt_update = $conexion->prepare("UPDATE producto_carrito SET cantidad = ? WHERE id_carrito = ? AND id_producto = ?");
            $stmt_update->bind_param("iii", $nueva_cantidad, $id_carrito, $id_producto);
            $stmt_update->execute();
            $stmt_update->close();
        } else {
            // Si no está, lo insertamos
            $stmt_insert = $conexion->prepare("INSERT INTO producto_carrito (id_carrito, id_producto, cantidad) VALUES (?, ?, 1)");
            $stmt_insert->bind_param("ii", $id_carrito, $id_producto);
            $stmt_insert->execute();
            $stmt_insert->close();
        }
    }
    
    // 5. VACIAR LA LISTA DE FAVORITOS
    $stmt_clear = $conexion->prepare("DELETE FROM lista_favoritos WHERE id_cliente = ?");
    $stmt_clear->bind_param("i", $id_cliente);
    $stmt_clear->execute();
    $stmt_clear->close();

    // Si todo salió bien, confirmamos los cambios
    $conexion->commit();
    $response['success'] = true;
    $response['message'] = '¡Todos tus favoritos han sido movidos al carrito!';
    $response['redirectUrl'] = '/shopnext/ShopNext-Beta/views/user/cart/carrito.php';

} catch (Exception $e) {
    // Si algo falla, revertimos todos los cambios
    $conexion->rollback();
    $response['message'] = $e->getMessage();
    http_response_code(500);
}

$conexion->close();
echo json_encode($response);
?>