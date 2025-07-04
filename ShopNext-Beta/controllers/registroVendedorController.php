<?php
// controllers/registroVendedorController.php

session_start();

// Dependencias de PHPMailer (necesarias para enviar correos)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../core/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../core/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../core/PHPMailer/src/SMTP.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    header("Location: /ShopNext/ShopNext-Beta/views/auth/signUpVendedor.html?error=conexion");
    exit;
}

// 1. Recopilación y validación de datos
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['email'] ?? '');
$clave = trim($_POST['contrasena'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$fecha_registro = date('Y-m-d');
$rol = 'vendedor';

if (empty($nombre) || empty($correo) || empty($clave) || empty($telefono)) {
    header("Location: ../views/auth/signUpVendedor.html?error=vacio");
    exit;
}

// 2. Verificar si el correo ya existe
$stmt_check = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
$stmt_check->bind_param("s", $correo);
$stmt_check->execute();
$stmt_check->store_result();
if ($stmt_check->num_rows > 0) {
    header("Location: ../views/auth/signUpVendedor.html?error=correo");
    $stmt_check->close();
    $conexion->close();
    exit;
}
$stmt_check->close();

// 3. Crear el usuario en la tabla `usuario`
$clave_hash = password_hash($clave, PASSWORD_DEFAULT);
$token_verificacion = bin2hex(random_bytes(32));
$stmt_usuario = $conexion->prepare("INSERT INTO usuario (correo_usuario, contraseña, fecha_registro, estado, rol, verificado, token_verificacion) VALUES (?, ?, ?, 'activo', ?, 0, ?)");
$stmt_usuario->bind_param("sssss", $correo, $clave_hash, $fecha_registro, $rol, $token_verificacion);
$stmt_usuario->execute();
$id_usuario = $conexion->insert_id;
$stmt_usuario->close();

// 4. Insertar en la tabla `vendedor`
$stmt_vendedor = $conexion->prepare("INSERT INTO vendedor (nombre, telefono, id_usuario) VALUES (?, ?, ?)");
$stmt_vendedor->bind_param("ssi", $nombre, $telefono, $id_usuario);
$stmt_vendedor->execute();
$stmt_vendedor->close();

// === 5. BLOQUE DE ENVÍO DE CORREO (LA PARTE QUE FALTABA) ===
$mail = new PHPMailer(true);
$enlace_verificacion = "http://localhost/shopnext/ShopNext-Beta/controllers/verificarEmail.php?token=" . $token_verificacion;

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
    $mail->Subject = 'Verifica tu cuenta de Vendedor en ShopNext';
    $mail->Body = "<h1>¡Hola {$nombre}, bienvenido a ShopNext!</h1><p>Tu cuenta de vendedor ha sido creada. Por favor, haz clic en el siguiente enlace para verificar tu correo y activarla:</p><a href='{$enlace_verificacion}'>Verificar mi cuenta</a>";
    $mail->send();
    
    // Si el correo se envía bien, redirigimos pidiendo que verifiquen
    header("Location: ../views/auth/login.php?status=verificar_correo");

} catch (Exception $e) {
    // Si el correo falla, igual lo redirigimos
    header("Location: ../views/auth/login.php?error=email_no_enviado");
}

$conexion->close();
exit;
?>