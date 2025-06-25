<?php
// Conexión a la base de datos MySQL: servidor, usuario, contraseña, base de datos
$conexion = new mysqli("localhost", "root", "", "tienda");

// Verifica si ocurrió un error al conectar
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Captura la acción que se envía por la URL (listar, agregar, actualizar, eliminar)
$action = $_GET['action'] ?? '';

// Dependiendo de la acción solicitada, se ejecuta el procedimiento correspondiente
switch ($action) {

    // 📦 Listar productos del carrito
    case 'listar':
        // Llama al procedimiento almacenado que obtiene el carrito
        $resultado = $conexion->query("CALL sp_obtenerCarrito()");
        // Convierte los resultados en un arreglo asociativo
        $datos = $resultado->fetch_all(MYSQLI_ASSOC);
        // Devuelve los datos como JSON al cliente
        echo json_encode($datos);
        break;

    // ➕ Agregar un producto al carrito
    case 'agregar':
        // Obtiene los datos enviados por POST (producto, precio, cantidad)
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        // Prepara la llamada al procedimiento almacenado
        $stmt = $conexion->prepare("CALL sp_agregarProducto(?, ?, ?)");
        // Asocia los parámetros a la consulta (s = string, d = double, i = integer)
        $stmt->bind_param("sdi", $producto, $precio, $cantidad);
        $stmt->execute();
        // Devuelve una respuesta JSON de éxito
        echo json_encode(["status" => "ok"]);
        break;

    // 🔄 Actualizar cantidad de un producto
    case 'actualizar':
        $id = $_POST['id'];
        $cantidad = $_POST['cantidad'];
        // Llama al procedimiento para actualizar cantidad
        $stmt = $conexion->prepare("CALL sp_actualizarCantidad(?, ?)");
        $stmt->bind_param("ii", $id, $cantidad);
        $stmt->execute();
        echo json_encode(["status" => "ok"]);
        break;

    // ❌ Eliminar un producto del carrito
    case 'eliminar':
        $id = $_POST['id'];
        // Llama al procedimiento que elimina el producto del carrito
        $stmt = $conexion->prepare("CALL sp_eliminarProducto(?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["status" => "ok"]);
        break;
}
?>
