<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario_db = "root";
$contrasena_db = "";
$nombre_db = "shopnexs";

// Crear la conexión
$conexion = mysqli_connect($servidor, $usuario_db, $contrasena_db, $nombre_db);

// Verificar si la conexión falló
if (!$conexion) {
    // Detener todo y mostrar un error claro si la conexión no se puede establecer
    die("Error de conexión fallido: " . mysqli_connect_error());
}

// Establecer el juego de caracteres a UTF-8 (esencial para tildes y ñ)
mysqli_set_charset($conexion, "utf8");
?>