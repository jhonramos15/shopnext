<?php
// Este archivo se encarga de buscar un producto por su ID en la base de datos
// y devolver sus datos en formato JSON para que se pueda usar desde JavaScript.

// Establece el tipo de contenido de la respuesta a 'application/json'.
// Esto le dice al navegador o a la aplicación que la respuesta es un objeto JSON, no HTML.
header('Content-Type: application/json');

// Inicia o reanuda la sesión actual. Es necesario para acceder a las variables de $_SESSION.
session_start();

// --- BARRERA DE SEGURIDAD ---
// Se verifica que se cumplan tres condiciones antes de continuar:
// 1. Que el 'id_usuario' exista en la sesión (que el usuario haya iniciado sesión).
// 2. Que el 'rol' en la sesión sea 'admin'.
// 3. Que se haya enviado un 'id' a través de la URL (método GET).
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin' || !isset($_GET['id'])) {
    // Si alguna de las condiciones falla, se envía una respuesta JSON de error.
    echo json_encode(['success' => false, 'error' => 'Acceso denegado o ID no proporcionado.']);
    // Detiene la ejecución del script para no procesar nada más.
    exit;
}

// --- PROCESAMIENTO DE DATOS ---

// Obtiene el 'id' de la URL y lo convierte a un número entero usando intval().
// Esto es una medida de seguridad para asegurar que solo trabajamos con números.
$id_producto = intval($_GET['id']);

// Crea una nueva conexión a la base de datos usando mysqli.
// Parámetros: (servidor, usuario, contraseña, nombre_de_la_bd).
// Nota: Este código usa mysqli, no PDO. Ambas son formas de conectar a la BD.
$conexion = new mysqli("localhost", "root", "", "shopnexs");

// Prepara la consulta SQL para evitar inyección SQL.
// El signo '?' actúa como un marcador de posición seguro para los datos que se insertarán después.
$stmt = $conexion->prepare("SELECT nombre_producto, descripcion, precio, stock FROM producto WHERE id_producto = ?");

// Vincula la variable $id_producto al marcador de posición '?'.
// El parámetro "i" significa que la variable es de tipo 'integer' (entero).
$stmt->bind_param("i", $id_producto);

// Ejecuta la consulta preparada en la base de datos.
$stmt->execute();

// Obtiene el conjunto de resultados de la consulta ejecutada.
$resultado = $stmt->get_result();

// --- RESPUESTA ---

// Comprueba si se encontró algún producto y lo extrae como un array asociativo.
// Un array asociativo usa los nombres de las columnas como claves (ej: $producto['nombre_producto']).
if ($producto = $resultado->fetch_assoc()) {
    // Si se encontró, envía una respuesta JSON exitosa con los datos del producto.
    echo json_encode(['success' => true, 'producto' => $producto]);
} else {
    // Si no se encontró ningún producto con ese ID, envía una respuesta JSON de error.
    echo json_encode(['success' => false, 'error' => 'Producto no encontrado.']);
}

// --- LIMPIEZA ---
// Cierra la sentencia preparada para liberar recursos del servidor.
$stmt->close();
// Cierra la conexión a la base de datos.
$conexion->close();

?>