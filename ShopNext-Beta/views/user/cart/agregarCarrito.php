<?php
session_start();

// Preparamos una respuesta por defecto
$response = [
    'status' => 'error',
    'message' => 'Ocurrió un error inesperado.'
];

// 1. Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    $response['message'] = 'Debes iniciar sesión para añadir productos.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// 2. Verificar que recibimos los datos necesarios (ID del producto y cantidad)
if (isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    $producto_id = filter_var($_POST['producto_id'], FILTER_VALIDATE_INT);
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);

    if ($producto_id && $cantidad > 0) {
        // Inicializa el carrito en la sesión si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Si el producto ya está en el carrito, suma la cantidad. Si no, lo añade.
        if (isset($_SESSION['carrito'][$producto_id])) {
            $_SESSION['carrito'][$producto_id] += $cantidad;
        } else {
            $_SESSION['carrito'][$producto_id] = $cantidad;
        }

        $response = [
            'status' => 'success',
            'message' => 'Producto añadido correctamente.'
        ];

    } else {
        $response['message'] = 'Datos inválidos.';
    }
} else {
    $response['message'] = 'Faltan parámetros.';
}

// 3. Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);