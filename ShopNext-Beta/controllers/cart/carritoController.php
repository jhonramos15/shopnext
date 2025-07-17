<?php
session_start();
header('Content-Type: application/json'); // Siempre responderemos con JSON

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;

// Inicializamos el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

switch ($action) {
    case 'agregar':
        $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
        if ($id_producto > 0 && $cantidad > 0) {
            $_SESSION['carrito'][$id_producto] = ($_SESSION['carrito'][$id_producto] ?? 0) + $cantidad;
            echo json_encode(['success' => true, 'message' => 'Producto añadido al carrito.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Datos de producto inválidos.']);
        }
        break;

    case 'eliminar':
        if ($id_producto > 0 && isset($_SESSION['carrito'][$id_producto])) {
            unset($_SESSION['carrito'][$id_producto]);
            echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El producto no se encontró en el carrito.']);
        }
        break;

    case 'vaciar':
        $_SESSION['carrito'] = [];
        echo json_encode(['success' => true, 'message' => 'El carrito ha sido vaciado.']);
        break;
    
    case 'actualizar':
        if (isset($_POST['cantidades']) && is_array($_POST['cantidades'])) {
            foreach ($_POST['cantidades'] as $id => $cantidad) {
                $id_prod = (int)$id;
                $cant = (int)$cantidad;
                if (isset($_SESSION['carrito'][$id_prod]) && $cant > 0) {
                    $_SESSION['carrito'][$id_prod] = $cant;
                }
            }
            echo json_encode(['success' => true, 'message' => 'Carrito actualizado.']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No se recibieron datos para actualizar.']);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida.']);
        break;
}