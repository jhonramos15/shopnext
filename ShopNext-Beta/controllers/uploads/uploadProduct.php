<?php
// Inicia la sesión
session_start();

// 1. Incluye tu archivo de conexión PDO
require_once __DIR__ . '/../../config/conexion_pdo.php';

// 2. Crea una instancia de la clase y obtén la conexión
$database = new Database();
$pdo = $database->getConnection();

// Verifica si la conexión es exitosa
if (!$pdo) {
    header("Location: ../../views/vendedor/subirproductos.php?error=db_connection_failed");
    exit('La conexión a la base de datos falló.');
}

// 3. Procede solo si se envió el formulario
if (isset($_POST["subir_producto"])) {

    // Recopila los datos del formulario (saneados)
    $nombre_producto = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING); // El campo se llama 'titulo' en HTML
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $id_vendedor = $_SESSION['vendedor_id'] ?? 1; // Usamos 1 como fallback

    // Validación
    if (empty($nombre_producto) || empty($categoria) || empty($descripcion) || $precio === false) {
        header("Location: ../../views/vendedor/subirproductos.php?error=faltan_datos");
        exit();
    }

    try {
        $pdo->beginTransaction();

        // 4. CORRECCIÓN: Usa los nombres de columna correctos de tu tabla
        $sqlProducto = "INSERT INTO producto (nombre_producto, descripcion, precio, categoria, id_vendedor) VALUES (?, ?, ?, ?, ?)";
        $stmtProducto = $pdo->prepare($sqlProducto);
        
        // Ejecuta la inserción con los datos correctos
        $stmtProducto->execute([$nombre_producto, $descripcion, $precio, $categoria, $id_vendedor]);

        $idProducto = $pdo->lastInsertId();

        // Procesa las imágenes (esto asume que tienes la tabla 'imagenes_producto')
        $directorioUploads = __DIR__ . '/../../../public/uploads/products/';
        if (!file_exists($directorioUploads)) {
            mkdir($directorioUploads, 0777, true);
        }

        if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['name'][0])) {
            // Asegúrate de tener una tabla llamada 'imagenes_producto'
            $sqlImagen = "INSERT INTO imagenes_producto (producto_id, ruta_imagen) VALUES (?, ?)";
            $stmtImagen = $pdo->prepare($sqlImagen);
            
            $totalImagenes = count($_FILES['imagen']['name']);
            for ($i = 0; $i < $totalImagenes; $i++) {
                $nombreArchivo = uniqid('prod_') . '_' . basename($_FILES['imagen']['name'][$i]);
                $rutaDestino = $directorioUploads . $nombreArchivo;
                $rutaParaDB = 'uploads/products/' . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'][$i], $rutaDestino)) {
                    $stmtImagen->execute([$idProducto, $rutaParaDB]);
                }
            }
        }

        $pdo->commit();
        header("Location: ../../views/vendedor/productos.php?success=producto_subido");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        // Para depurar, puedes mostrar el error real
        exit('Error al insertar en la base de datos: ' . $e->getMessage()); 
    } finally {
        $pdo = null;
    }
} else {
    header("Location: ../../views/vendedor/subirproductos.php");
    exit();
}
?>