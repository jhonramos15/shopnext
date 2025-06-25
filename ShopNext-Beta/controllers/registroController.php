<?php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "mensaje" => "Error en la conexión a la base de datos."]);
    exit;
}

$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$clave = $_POST['clave'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$tipo = $_POST['tipo'] ?? 'cliente';
$fecha_registro = date('Y-m-d');

// Verificar si correo ya existe
$stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "mensaje" => "El correo ya está registrado."]);
    $stmt->close();
    $conexion->close();
    return;
}
$stmt->close();

// Crear usuario
$clave_hash = password_hash($clave, PASSWORD_DEFAULT);
$stmt = $conexion->prepare("INSERT INTO usuario (correo_usuario, contraseña, fecha_registro, estado) VALUES (?, ?, ?, 'activo')");
$stmt->bind_param("sss", $correo, $clave_hash, $fecha_registro);

if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "mensaje" => "Error al registrar usuario."]);
    $stmt->close();
    $conexion->close();
    return;
}

$id_usuario = $conexion->insert_id;
$stmt->close();

// Si es cliente, registrarlo en la tabla cliente
if ($tipo === "cliente") {
    $stmt = $conexion->prepare("INSERT INTO cliente (nombre, direccion, fecha_registro, id_usuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $direccion, $fecha_registro, $id_usuario);

    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "mensaje" => "Error al registrar cliente."]);
        $stmt->close();
        $conexion->close();
        return;
    }

    $stmt->close();
}

// ✅ Todo salió bien
echo json_encode(["status" => "ok", "mensaje" => "Cliente registrado con éxito."]);
$conexion->close();
