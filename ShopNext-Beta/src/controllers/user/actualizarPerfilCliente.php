<?php
// controllers/actualizarPerfilCliente.php (Versión Final y Corregida)
session_start();
require_once __DIR__ . '/authGuardCliente.php'; // Tu guardián de seguridad

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../core/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../core/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../core/PHPMailer/src/SMTP.php';

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) { die("Error de conexión."); }

$id_usuario_actual = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- RECOPILACIÓN DE DATOS ---
    $nombre = trim($_POST['nombre']);
    $correo_nuevo = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $genero = $_POST['genero'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    
    // --- 1. ACTUALIZACIÓN DE DATOS BÁSICOS DEL PERFIL (SIEMPRE SE HACE PRIMERO) ---
    // (Manejo de la foto de perfil)
    $stmt_foto = $conexion->prepare("SELECT foto_perfil FROM cliente WHERE id_usuario = ?");
    $stmt_foto->bind_param("i", $id_usuario_actual);
    $stmt_foto->execute();
    $nombre_foto_actual = $stmt_foto->get_result()->fetch_assoc()['foto_perfil'];
    $nombre_foto_para_db = $nombre_foto_actual;

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $directorio_avatares = $_SERVER['DOCUMENT_ROOT'] . '/shopnext/ShopNext-Beta/public/uploads/avatars/';
        if (!file_exists($directorio_avatares)) { mkdir($directorio_avatares, 0777, true); }
        $nombre_unico = uniqid('avatar_') . '.' . pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $directorio_avatares . $nombre_unico)) {
            $nombre_foto_para_db = $nombre_unico;
        }
    }
    
    // Actualizamos la tabla cliente
    $stmt_cliente = $conexion->prepare("UPDATE cliente SET nombre = ?, telefono = ?, genero = ?, fecha_nacimiento = ?, foto_perfil = ? WHERE id_usuario = ?");
    $stmt_cliente->bind_param("sssssi", $nombre, $telefono, $genero, $fecha_nacimiento, $nombre_foto_para_db, $id_usuario_actual);
    $stmt_cliente->execute();
    $stmt_cliente->close();

    // --- 2. LÓGICA DE CAMBIO DE CONTRASEÑA (SE VERIFICA POR SEPARADO) ---
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    if (!empty($current_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        // ... (Tu código para validar y cambiar la contraseña va aquí. Ya estaba bien) ...
    }

    // --- 3. LÓGICA DE CAMBIO DE CORREO (SE VERIFICA AL FINAL) ---
    $stmt_correo = $conexion->prepare("SELECT correo_usuario FROM usuario WHERE id_usuario = ?");
    $stmt_correo->bind_param("i", $id_usuario_actual);
    $stmt_correo->execute();
    $correo_actual = $stmt_correo->get_result()->fetch_assoc()['correo_usuario'];
    $stmt_correo->close();

    if ($correo_nuevo !== $correo_actual) {
        // Si el correo es diferente, iniciamos el proceso de verificación
        $token_cambio = bin2hex(random_bytes(32));
        $stmt_temp = $conexion->prepare("UPDATE usuario SET nuevo_correo = ?, token_cambio_correo = ? WHERE id_usuario = ?");
        $stmt_temp->bind_param("ssi", $correo_nuevo, $token_cambio, $id_usuario_actual);
        $stmt_temp->execute();
        $stmt_temp->close();

        // Enviar el correo de verificación AL NUEVO CORREO
        $enlace_verificacion = "http://localhost/shopnext/ShopNext-Beta/controllers/verificarCambioEmail.php?token=" . $token_cambio;
        $mail = new PHPMailer(true);
        try {
            // ... (Tu configuración de PHPMailer) ...
            $mail->addAddress($correo_nuevo);
            $mail->Subject = 'Confirma tu nuevo email en ShopNext';
            $mail->Body    = "<h1>Confirma tu cambio de email</h1><p>Haz clic en el siguiente enlace para confirmar tu nuevo correo:</p><a href='{$enlace_verificacion}'>Confirmar nuevo email</a>";
            $mail->send();
            
            header("Location: ../views/pages/account.php?status=reverificar_correo");
            exit;
        } catch (Exception $e) {
            header("Location: ../views/pages/account.php?error=error_envio");
            exit;
        }
    }
    
    // Si llegamos hasta aquí y no hubo cambio de correo, mostramos el mensaje de éxito general
    header("Location: ../views/pages/account.php?status=ok");
    exit;
}
?>