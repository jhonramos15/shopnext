<?php
// controllers/cart/carritoController.php

session_start();

header('Content-Type: application/json');
$response = ['success' => false, 'error' => 'Un error inesperado ha ocurrido.'];

if (!isset($_SESSION['id_usuario'])) {
    $response['error'] = 'login_required';
    echo json_encode($response);
    exit;
}

if (isset($_POST['id_producto']) && isset($_POST['cantidad'])) {
    $id_producto = filter_var($_POST['id_producto'], FILTER_VALIDATE_INT);
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);

    if ($id_producto && $cantidad > 0) {
        $conexion = new mysqli("localhost", "root", "", "shopnexs");
        if ($conexion->connect_error) {
            $response['error'] = 'Error de conexión a la base de datos.';
            echo json_encode($response);
            exit;
        }

        $id_usuario = $_SESSION['id_usuario'];
        $id_cliente = null;
        $id_carrito = null;

        $conexion->begin_transaction();

        try {
            // 1. Obtener id_cliente desde id_usuario
            $stmt_cliente = $conexion->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
            $stmt_cliente->bind_param("i", $id_usuario);
            $stmt_cliente->execute();
            $resultado_cliente = $stmt_cliente->get_result();
            if ($resultado_cliente->num_rows > 0) {
                $id_cliente = $resultado_cliente->fetch_assoc()['id_cliente'];
            }
            $stmt_cliente->close();

            if (!$id_cliente) {
                throw new Exception("No se encontró el perfil del cliente.");
            }

            // 2. Obtener o crear el carrito para el cliente
            $stmt_carrito = $conexion->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
            $stmt_carrito->bind_param("i", $id_cliente);
            $stmt_carrito->execute();
            $resultado_carrito = $stmt_carrito->get_result();
            if ($resultado_carrito->num_rows > 0) {
                $id_carrito = $resultado_carrito->fetch_assoc()['id_carrito'];
            } else {
                $stmt_crear_carrito = $conexion->prepare("INSERT INTO carrito (id_cliente) VALUES (?)");
                $stmt_crear_carrito->bind_param("i", $id_cliente);
                $stmt_crear_carrito->execute();
                $id_carrito = $conexion->insert_id;
                $stmt_crear_carrito->close();
            }
            $stmt_carrito->close();

            // 3. Verificar si el producto ya está en el carrito (en la BD)
            $stmt_producto_existente = $conexion->prepare("SELECT id_producto_carrito, cantidad FROM producto_carrito WHERE id_carrito = ? AND id_producto = ?");
            $stmt_producto_existente->bind_param("ii", $id_carrito, $id_producto);
            $stmt_producto_existente->execute();
            $resultado_producto = $stmt_producto_existente->get_result();

            if ($resultado_producto->num_rows > 0) {
                $producto_en_carrito = $resultado_producto->fetch_assoc();
                $nueva_cantidad = $producto_en_carrito['cantidad'] + $cantidad;
                $stmt_actualizar = $conexion->prepare("UPDATE producto_carrito SET cantidad = ? WHERE id_producto_carrito = ?");
                $stmt_actualizar->bind_param("ii", $nueva_cantidad, $producto_en_carrito['id_producto_carrito']);
                $stmt_actualizar->execute();
                $stmt_actualizar->close();
            } else {
                $stmt_insertar = $conexion->prepare("INSERT INTO producto_carrito (id_carrito, id_producto, cantidad) VALUES (?, ?, ?)");
                $stmt_insertar->bind_param("iii", $id_carrito, $id_producto, $cantidad);
                $stmt_insertar->execute();
                $stmt_insertar->close();
            }
            $stmt_producto_existente->close();

            $conexion->commit();
            $response = ['success' => true, 'message' => 'Producto añadido al carrito.'];

        } catch (Exception $e) {
            $conexion->rollback();
            $response['error'] = 'Error al procesar el carrito: ' . $e->getMessage();
        }

        $conexion->close();

    } else {
        $response['error'] = 'Datos de producto inválidos.';
    }
} else {
    $response['error'] = 'Faltan parámetros.';
}

echo json_encode($response);
exit;