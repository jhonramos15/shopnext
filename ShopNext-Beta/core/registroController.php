<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];
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

// Insertar en tabla cliente o vendedor
if ($tipo === "cliente") {
    $stmt = $conexion->prepare("INSERT INTO cliente (nombre, direccion, fecha_registro, id_usuario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $direccion, $fecha_registro, $id_usuario);
    $stmt->execute();
    echo "Cliente registrado con éxito.";
} else {
    $stmt = $conexion->prepare("INSERT INTO vendedor (nombre, direccion, correo, telefono, fecha_registro, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nombre, $direccion, $correo, $telefono, $fecha_registro, $id_usuario);
    $stmt->execute();
    echo "Vendedor registrado con éxito.";
}
$stmt->close();
$conexion->close();

// Enviar correo de bienvenida
$mail = new PHPMailer(true);
try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Hosting
        $mail->SMTPAuth = true;
        $mail->Username = 'shopnextnoreply@gmail.com'; // Correo que enviará la bienvenida
        $mail->Password = 'uhym jhjw dzym pyyf'; // Contraseña Google generada
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587; // Puerto (no tocar)

    $mail->setFrom('tucorreo@gmail.com', 'ShopNext');
    $mail->addAddress($correo, $nombre);

    $mail->isHTML(true);
    $mail->Subject = '¡Bienvenido a ShopNext!';
    $mail->Body    = "<h3>Hola $nombre,</h3><p>Gracias por registrarte en <strong>ShopNexs</strong>. ¡Esperamos que disfrutes de tu experiencia!</p>";
    $mail->AltBody = "Hola $nombre, gracias por registrarte en ShopNext.";

    $mail->send();
} catch (Exception $e) {
    // Puedes loguear el error si quieres: $mail->ErrorInfo
}
?>
