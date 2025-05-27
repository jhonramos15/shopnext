<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recibir los datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];
$direccion = $_POST['direccion'] ?? '';
$fecha_registro = date('Y-m-d');

// Validar que el correo exista
$stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "El correo ya está registrado.";
    $stmt->close();
    $conexion->close();
    exit;
}
$stmt->close();

// Cifrar la contraseña
$clave_hash = password_hash($clave, PASSWORD_DEFAULT);

// Insertar en tabla usuario
$stmt = $conexion->prepare("INSERT INTO usuario (correo_usuario, contraseña, fecha_registro, estado) VALUES (?, ?, ?, 'activo')");
$stmt->bind_param("sss", $correo, $clave_hash, $fecha_registro);

if (!$stmt->execute()) {
    echo "Error al crear usuario: " . $stmt->error;
    $stmt->close();
    $conexion->close();
    exit;
}

$id_usuario = $conexion->insert_id;
$stmt->close();

// Insertar en tabla cliente
$stmt = $conexion->prepare("INSERT INTO cliente (nombre, direccion, fecha_registro, id_usuario) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $nombre, $direccion, $fecha_registro, $id_usuario);

if ($stmt->execute()) {
    header("Location: ../html/login.html");
    exit;
    echo "Cuenta registrada con éxito";
} else {
    echo "Error al crear cliente: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
