<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "tienda");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Captura la acción solicitada
$action = $_GET['action'] ?? '';

switch ($action) {

    // Agregar un producto a la wishlist
    case 'agregar':
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $imagen = $_POST['imagen'];
        $stmt = $conexion->prepare("CALL sp_agregarDeseo(?, ?, ?)");
        $stmt->bind_param("sis", $producto, $precio, $imagen);
        $stmt->execute();
        echo json_encode(["status" => "ok", "message" => "Añadido a la lista de deseos"]);
        break;

    // Listar todos los productos de la wishlist
    case 'listar':
        $resultado = $conexion->query("CALL sp_obtenerDeseos()");
        $datos = $resultado->fetch_all(MYSQLI_ASSOC);
        echo json_encode($datos);
        break;

    // Contar los productos en la wishlist
    case 'contar':
        $resultado = $conexion->query("CALL sp_contarDeseos()");
        $fila = $resultado->fetch_assoc();
        echo json_encode($fila);
        break;

    // Eliminar un producto de la wishlist
    case 'eliminar':
        $id = $_POST['id'];
        $stmt = $conexion->prepare("CALL sp_eliminarDeseo(?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["status" => "ok"]);
        break;

    // Mover un producto individual al carrito
    case 'mover_uno':
        $id = $_POST['id'];
        $stmt = $conexion->prepare("CALL sp_moverDeseoACarrito(?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["status" => "ok"]);
        break;

    // Mover todos los productos al carrito
    case 'mover_todo':
        $conexion->query("CALL sp_moverTodoACarrito()");
        echo json_encode(["status" => "ok"]);
        break;
}
?>