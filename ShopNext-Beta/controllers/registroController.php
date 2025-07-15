<?php
// controllers/registroController.php

session_start();

// Dependencias de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Se incluyen los archivos de PHPMailer directamente
require_once __DIR__ . '/../core/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../core/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../core/PHPMailer/src/SMTP.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=conexion");
    exit;
}

// 1. Recopilación de TODOS los datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$genero = $_POST['genero'] ?? '';
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
$correo = trim($_POST['correo'] ?? '');
$clave = $_POST['clave'] ?? '';
$tipo = 'cliente';
$fecha_registro = date('Y-m-d');

// 2. Validación más completa
if (empty($nombre) || empty($correo) || empty($clave) || empty($telefono) || empty($direccion)) {
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=vacio");
    exit;
}

// 3. Verificar si el correo ya existe
$stmt_check = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
$stmt_check->bind_param("s", $correo);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=correo");
    $stmt_check->close();
    $conexion->close();
    exit;
}
$stmt_check->close();

// 4. Crear el usuario en la base de datos
$clave_hash = password_hash($clave, PASSWORD_DEFAULT);
$token_verificacion = bin2hex(random_bytes(32)); // Generamos el token aquí
$stmt_usuario = $conexion->prepare("INSERT INTO usuario (correo_usuario, contraseña, fecha_registro, estado, rol, verificado, token_verificacion) VALUES (?, ?, ?, 'activo', ?, 0, ?)");
$stmt_usuario->bind_param("sssss", $correo, $clave_hash, $fecha_registro, $tipo, $token_verificacion);

if (!$stmt_usuario->execute()) {
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=registro_usuario");
    $stmt_usuario->close();
    $conexion->close();
    exit;
}

$id_usuario = $conexion->insert_id;
$stmt_usuario->close();

// 5. Insertar en la tabla `cliente` con TODOS los datos
$stmt_cliente = $conexion->prepare("INSERT INTO cliente (nombre, direccion, telefono, genero, fecha_nacimiento, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
$stmt_cliente->bind_param("sssssi", $nombre, $direccion, $telefono, $genero, $fecha_nacimiento, $id_usuario);

if (!$stmt_cliente->execute()) {
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=registro_cliente");
    exit;
}
$stmt_cliente->close();


// 6. Enviar el correo de verificación
$mail = new PHPMailer(true);
// 1. Determinar el protocolo (http o https)
$protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";

// 2. Obtener el nombre del host (ej: localhost o www.tusitio.com)
$host = $_SERVER['SERVER_ADDR']; 

// 3. Construir la ruta base del proyecto dinámicamente
// dirname($_SERVER['PHP_SELF'], 2) sube dos niveles desde /controllers/ para llegar a la raíz del proyecto
$ruta_base = dirname($_SERVER['PHP_SELF'], 2);

// 4. Crear el enlace de verificación completo y dinámico
$enlace_verificacion = "{$protocolo}://{$host}{$ruta_base}/controllers/verificarEmail.php?token={$token_verificacion}";

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'shopnextnoreply@gmail.com';
    $mail->Password = 'uhym jhjw dzym pyyf';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('shopnextnoreply@gmail.com', 'ShopNext');
    $mail->addAddress($correo, $nombre);

    $mail->isHTML(true);
    $mail->Subject = 'Verifica tu cuenta en ShopNext';
    $mail->Body = "<h1>¡Bienvenido a ShopNext, {$nombre}!</h1><p>Gracias por registrarte. Por favor, haz clic en el siguiente enlace para activar tu cuenta:</p><a href='{$enlace_verificacion}'>Verificar mi cuenta</a>";
    $mail->send();
    
    // Si todo sale bien, redirigir a la página de login pidiendo que verifiquen el correo
    header("Location: ../views/auth/login.php?status=verificar_correo");

} catch (Exception $e) {
    // Si el correo no se puede enviar, igual el usuario está creado.
    header("Location: ../views/auth/login.php?error=email_no_enviado");
}

$conexion->close();
exit;
?>