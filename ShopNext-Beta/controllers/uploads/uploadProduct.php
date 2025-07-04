<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../../views/auth/login.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Conexión fallida: " . $conexion->connect_error); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario_session = $_SESSION['id_usuario'];
    
    // Obtener id_vendedor (esto ya está corregido)
    $stmt_vendedor = $conexion->prepare("SELECT id_vendedor FROM vendedor WHERE id_usuario = ?");
    $stmt_vendedor->bind_param("i", $id_usuario_session);
    $stmt_vendedor->execute();
    $vendedor = $stmt_vendedor->get_result()->fetch_assoc();
    $id_vendedor = $vendedor['id_vendedor'];
    $stmt_vendedor->close();

    // Recopilar datos (incluyendo el nuevo campo stock)
    $nombre_producto = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']); // <-- Obtenemos el stock
    $categoria = trim($_POST['categoria']);

    // Manejo de la imagen (sin cambios)
    $nombre_imagen = '';
    if (isset($_FILES['imagen']['name'][0]) && $_FILES['imagen']['error'][0] == 0) {
        $directorio_destino = "../../../public/uploads/products/";
        if (!file_exists($directorio_destino)) { mkdir($directorio_destino, 0777, true); }
        $extension = pathinfo($_FILES['imagen']['name'][0], PATHINFO_EXTENSION);
        $nombre_imagen = uniqid('prod_') . '.' . $extension;
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'][0], $directorio_destino . $nombre_imagen)) {
             header("Location: ../../views/dashboard/vendedor/subirProductos.php?error=upload_failed");
             exit;
        }
    }

    // Insertar en la base de datos (con el nuevo campo stock)
    $stmt = $conexion->prepare(
        "INSERT INTO producto (nombre_producto, descripcion, precio, stock, categoria, id_vendedor, ruta_imagen) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    // Añadimos una 'i' para el stock (integer)
    $stmt->bind_param("ssdisis", $nombre_producto, $descripcion, $precio, $stock, $categoria, $id_vendedor, $nombre_imagen);

    if ($stmt->execute()) {
        header("Location: ../../views/dashboard/vendedor/productos.php?status=product_ok");
    } else {
        header("Location: ../../views/dashboard/vendedor/subirProductos.php?error=db_error");
    }
    $stmt->close();
    $conexion->close();
}
?>