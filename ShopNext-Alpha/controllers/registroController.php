<?php
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    echo "Error en la conexión a la base de datos.";
    exit;
}

$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$clave = $_POST['clave'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$tipo = $_POST['tipo'] ?? 'cliente';
$fecha_registro = date('Y-m-d');

// Validar que el correo no esté registrado
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

// Crear usuario
$clave_hash = password_hash($clave, PASSWORD_DEFAULT);
$stmt = $conexion->prepare("INSERT INTO usuario (correo_usuario, contraseña, fecha_registro, estado) VALUES (?, ?, ?, 'activo')");
$stmt->bind_param("sss", $correo, $clave_hash, $fecha_registro);

if (!$stmt->execute()) {
    echo "Error al registrar usuario.";
    $stmt->close();
    $conexion->close();
    exit;
}

$id_usuario = $conexion->insert_id;
$stmt->close();

// Registrar según tipo
if ($tipo === "cliente") {
    $stmt = $conexion->prepare("INSERT INTO cliente (nombre, direccion, fecha_registro, id_usuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $direccion, $fecha_registro, $id_usuario);
    $stmt->execute();
    echo "Cliente registrado con éxito.";
} else if ($tipo === "vendedor") {
    $stmt = $conexion->prepare("INSERT INTO vendedor (nombre, direccion, correo, telefono, fecha_registro, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombre, $direccion, $correo, $telefono, $fecha_registro, $id_usuario);
    $stmt->execute();
    echo "Vendedor registrado con éxito.";
}

$stmt->close();
$conexion->close();
