<?php

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnext");

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recibir los datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];

// Insertar en la base de datos
$sql = "INSERT INTO usuarios (nombre, correo, clave) VALUES ('$nombre', '$correo', '$clave')";

if ($conexion->query($sql) === TRUE) {
    echo "Cuenta registrada con éxito";
} else {
    echo "Error: " . $conexion->error;
}

$conexion->close();
?>
