<?php
// Activar errores para depuración (quítalo en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
