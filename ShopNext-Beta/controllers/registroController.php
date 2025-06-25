<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../core/PHPMailer/src/Exception.php';
require __DIR__ . '/../core/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../core/PHPMailer/src/SMTP.php';

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
// Conexión fallida
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=conexion");
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$clave = $_POST['clave'] ?? '';
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$tipo = $_POST['tipo'] ?? 'cliente';
$fecha_registro = date('Y-m-d');

// 🛡️ Validación básica
if (empty($nombre) || empty($correo) || empty($clave)) {
// Campos vacíos
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=vacio");
    exit;
}

// Verificar si el correo ya está registrado
$stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
// Correo ya registrado
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=correo");
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
// Error al crear usuario
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=registro_usuario");
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
} else {
    $stmt = $conexion->prepare("INSERT INTO vendedor (nombre, direccion, correo, telefono, fecha_registro, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombre, $direccion, $correo, $telefono, $fecha_registro, $id_usuario);
}

if (!$stmt->execute()) {
// Error al crear cliente o vendedor
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=registro_perfil");
    $stmt->close();
    $conexion->close();
    exit;
}
$stmt->close();

// Enviar correo de bienvenida
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'shopnextnoreply@gmail.com';
    $mail->Password = 'uhym jhjw dzym pyyf';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('shopnextnoreply@gmail.com', 'ShopNext');
    $mail->addAddress($correo, $nombre);
    $mail->isHTML(true);
    $mail->Subject = '¡Bienvenido a ShopNext!';
    $mail->Body = "<h3>Hola $nombre,</h3><p>Gracias por registrarte en <strong>ShopNext</strong>. ¡Esperamos que disfrutes de tu experiencia!</p>";

    $mail->send();
} catch (Exception $e) {
    // El error de correo no interfiere con el registro
}

header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?status=ok");
exit;
