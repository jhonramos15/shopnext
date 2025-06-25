<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    header("Location: ../views/auth/signUp.html?error=conexion");
    exit;
}

$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$clave = $_POST['clave'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$tipo = $_POST['tipo'] ?? 'cliente';
$fecha_registro = date('Y-m-d');

if (empty($nombre) || empty($correo) || empty($clave)) {
    header("Location: ../views/auth/signUp.html?error=vacio");
    exit;
}

// Validar que el correo no esté registrado
$stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: ../views/auth/signUp.html?error=correo_usado");
    exit;
}
$stmt->close();

$clave_hash = password_hash($clave, PASSWORD_DEFAULT);
$stmt = $conexion->prepare("INSERT INTO usuario (correo_usuario, contraseña, fecha_registro, estado) VALUES (?, ?, ?, 'activo')");
$stmt->bind_param("sss", $correo, $clave_hash, $fecha_registro);

if (!$stmt->execute()) {
    header("Location: ../views/auth/signUp.html?error=crear_usuario");
    exit;
}

$id_usuario = $conexion->insert_id;
$stmt->close();

// Cliente o vendedor
if ($tipo === "cliente") {
    $stmt = $conexion->prepare("INSERT INTO cliente (nombre, direccion, fecha_registro, id_usuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $direccion, $fecha_registro, $id_usuario);
} else {
    $stmt = $conexion->prepare("INSERT INTO vendedor (nombre, direccion, correo, telefono, fecha_registro, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombre, $direccion, $correo, $telefono, $fecha_registro, $id_usuario);
}

if (!$stmt->execute()) {
    header("Location: ../views/auth/signUp.html?error=crear_perfil");
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
    // No afecta el registro
}

header("Location: ../views/auth/signUp.html?status=ok");
exit;
