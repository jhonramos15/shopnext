<?php
session_start();

// Guardián de seguridad (sin cambios)
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: ../../views/auth/login.php");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Conexión fallida: " . $conexion->connect_error); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario_session = $_SESSION['id_usuario'];

    // Obtener el id_vendedor (esto ya está correcto)
    $stmt_vendedor = $conexion->prepare("SELECT id_vendedor FROM vendedor WHERE id_usuario = ?");
    $stmt_vendedor->bind_param("i", $id_usuario_session);
    $stmt_vendedor->execute();
    $vendedor = $stmt_vendedor->get_result()->fetch_assoc();
    $id_vendedor = $vendedor['id_vendedor'];
    $stmt_vendedor->close();

    // Recopilar datos del formulario
    $nombre_producto = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $categoria = trim($_POST['categoria']);

    // --- MANEJO DE IMAGEN CORREGIDO Y MÁS SEGURO ---
    $nombre_imagen_para_db = ''; // Variable que guardaremos en la BD

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'][0] == 0) {
        
        // 1. Ruta física y absoluta donde se guardará la imagen en el servidor
        // dirname(__DIR__, 3) sube 3 niveles desde `controllers/uploads/` hasta la raíz del proyecto
        $directorio_servidor = dirname(__DIR__, 3) . '/public/uploads/products/';

        // 2. Crear el directorio si no existe
        if (!file_exists($directorio_servidor)) {
            mkdir($directorio_servidor, 0777, true);
        }

        // 3. Crear un nombre único para la imagen
        $nombre_unico = uniqid('prod_') . '.' . pathinfo($_FILES['imagen']['name'][0], PATHINFO_EXTENSION);
        $ruta_completa_servidor = $directorio_servidor . $nombre_unico;

        // 4. Mover el archivo a su destino final
        if (move_uploaded_file($_FILES['imagen']['tmp_name'][0], $ruta_completa_servidor)) {
            // ¡ÉXITO! Guardamos solo el nombre del archivo en la base de datos
            $nombre_imagen_para_db = $nombre_unico;
        } else {
            header("Location: ../../views/dashboard/vendedor/subirProductos.php?error=upload_failed");
            exit;
        }
    }

    // --- INSERTAR EN LA BASE DE DATOS ---
    $stmt = $conexion->prepare(
        "INSERT INTO producto (nombre_producto, descripcion, precio, stock, categoria, id_vendedor, ruta_imagen) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    // Pasamos solo el nombre del archivo a la base de datos
    $stmt->bind_param("ssdisis", $nombre_producto, $descripcion, $precio, $stock, $categoria, $id_vendedor, $nombre_imagen_para_db);

    if ($stmt->execute()) {
        header("Location: ../../views/dashboard/vendedor/productos.php?status=product_ok");
    } else {
        header("Location: ../../views/dashboard/vendedor/subirProductos.php?error=db_error");
    }
    $stmt->close();
    $conexion->close();
}
?>