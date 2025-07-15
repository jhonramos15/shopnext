<?php

session_start();
require_once __DIR__ . '/../../controllers/authGuardCliente.php'; 

// Conexión a la BD
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtenemos id_cliente e id_carrito
$id_usuario = $_SESSION['id_usuario'];
$stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
$stmt_cliente->bind_param("i", $id_usuario);
$stmt_cliente->execute();
$id_cliente = $stmt_cliente->get_result()->fetch_assoc()['id_cliente'];

$stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
$stmt_carrito->bind_param("i", $id_cliente);
$stmt_carrito->execute();
$id_carrito = $stmt_carrito->get_result()->fetch_assoc()['id_carrito'];

// Cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion->begin_transaction();
    try {
        $sql_items = "SELECT pc.id_producto, pc.cantidad, p.precio 
                      FROM producto_carrito pc 
                      JOIN producto p ON pc.id_producto = p.id_producto 
                      WHERE id_carrito = ?";

        $stmt_items = $conexion->prepare($sql_items);
        $stmt_items->bind_param("i", $id_carrito);
        $stmt_items->execute();
        $items = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);

        // Agrupamos por vendedor
        $pedidos_por_vendedor = [];
        foreach ($items as $item) {
            $stmt_vendedor = $conexion->prepare("SELECT id_vendedor FROM producto WHERE id_producto = ?");
            $stmt_vendedor->bind_param("i", $item['id_producto']);
            $stmt_vendedor->execute();
            $id_vendedor = $stmt_vendedor->get_result()->fetch_assoc()['id_vendedor'];

            if (!isset($pedidos_por_vendedor[$id_vendedor])) {
                $pedidos_por_vendedor[$id_vendedor] = [];
            }
            $pedidos_por_vendedor[$id_vendedor][] = $item;
        }

        // Creamos un pedido por cada vendedor
        foreach ($pedidos_por_vendedor as $id_vendedor => $productos_vendedor) {
            $stmt_pedido = $conexion->prepare("INSERT INTO pedido (id_cliente, id_vendedor, fecha, estado) VALUES (?, ?, NOW(), 'pendiente')");
            $stmt_pedido->bind_param("ii", $id_cliente, $id_vendedor);
            $stmt_pedido->execute();
            $id_pedido_nuevo = $conexion->insert_id;

            foreach ($productos_vendedor as $producto) {
                $stmt_detalle = $conexion->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
                $stmt_detalle->bind_param("iiid", $id_pedido_nuevo, $producto['id_producto'], $producto['cantidad'], $producto['precio']);
                $stmt_detalle->execute();

               $stmt_stock = $conexion->prepare("UPDATE producto SET stock = stock - ? WHERE id_producto = ?");
                $stmt_stock->bind_param("ii", $producto['cantidad'], $producto['id_producto']);
                $stmt_stock->execute();
            }
        }                

        // Vaciamos el carrito del usuario
        $stmt_vaciar = $conexion->prepare("DELETE FROM producto_carrito WHERE id_carrito = ?");
        $stmt_vaciar->bind_param("i", $id_carrito);
        $stmt_vaciar->execute();

        // Si todo va bien, confirmamos la transacción
        $conexion->commit();
        header("Location: /shopnext/ShopNext-Beta/views/user/pages/pedidos.php?status=compra_exitosa");
        exit;

    } catch (Exception $e) {
        $conexion->rollback();
        // Redirigimos con el mensaje de error para saber qué pasó
        header("Location: /shopnext/ShopNext-Beta/views/user/cart/checkout.php?error=Error al procesar el pedido: " . urlencode($e->getMessage()));
        exit;
    }
}
?>