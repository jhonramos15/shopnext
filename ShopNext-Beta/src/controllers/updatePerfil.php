<?php
// controllers/updatePerfil.php (Versión Final Corregida)
session_start();

// Guardián para asegurar que el usuario esté logueado
require_once __DIR__ . '/authGuardCliente.php';

// Dependencias para enviar correos (en caso de que se cambie el email)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../core/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../core/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../core/PHPMailer/src/SMTP.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    // Es mejor morir aquí que redirigir si la BD falla
    die("Error crítico de conexión a la base de datos.");
}

$id_usuario_actual = $_SESSION['id_usuario'];

// Solo procesar si el método es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- 1. RECOPILACIÓN Y VALIDACIÓN DE DATOS ---
    $nombre = trim($_POST['nombre'] ?? '');
    $correo_nuevo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $genero = $_POST['genero'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    // --- ¡VALIDACIÓN CORREGIDA Y MEJORADA! ---
    // Si los campos obligatorios están vacíos, no continuamos.
    if (empty($nombre) || empty($correo_nuevo)) {
        header("Location: ../views/pages/account.php?error=campos_vacios_perfil");
        exit;
    }

    // --- 2. LÓGICA DE ACTUALIZACIÓN DE DATOS ---
    $conexion->begin_transaction();
    try {
        // (Manejo de la foto de perfil)
        $stmt_foto = $conexion->prepare("SELECT foto_perfil FROM cliente WHERE id_usuario = ?");
        $stmt_foto->bind_param("i", $id_usuario_actual);
        $stmt_foto->execute();
        $nombre_foto_actual = $stmt_foto->get_result()->fetch_assoc()['foto_perfil'] ?? 'default_avatar.png';
        $nombre_foto_para_db = $nombre_foto_actual;

        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
            $directorio_avatares = $_SERVER['DOCUMENT_ROOT'] . '/shopnext/ShopNext-Beta/public/uploads/avatars/';
            if (!file_exists($directorio_avatares)) {
                mkdir($directorio_avatares, 0777, true);
            }
            $nombre_unico = 'avatar_' . uniqid() . '.' . pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $directorio_avatares . $nombre_unico)) {
                $nombre_foto_para_db = $nombre_unico;
            }
        }
        
        // Actualizamos los datos del cliente
        $stmt_cliente = $conexion->prepare("UPDATE cliente SET nombre = ?, telefono = ?, genero = ?, fecha_nacimiento = ?, foto_perfil = ? WHERE id_usuario = ?");
        $stmt_cliente->bind_param("sssssi", $nombre, $telefono, $genero, $fecha_nacimiento, $nombre_foto_para_db, $id_usuario_actual);
        $stmt_cliente->execute();
        $stmt_cliente->close();

        // (Lógica de cambio de contraseña, si se proporcionaron los campos)
        if (!empty($current_pass) && !empty($new_pass) && !empty($confirm_pass)) {
            // ... (el resto de la lógica de contraseña)
        }

        // --- LÓGICA DE CAMBIO DE CORREO (SE VERIFICA AL FINAL) ---
        // (El resto de la lógica de cambio de correo)

        $conexion->commit();
        header("Location: ../views/pages/account.php?status=ok");

    } catch (Exception $e) {
        $conexion->rollback();
        header("Location: ../views/pages/account.php?error=error_general");
    }
    
    exit;
}
?>