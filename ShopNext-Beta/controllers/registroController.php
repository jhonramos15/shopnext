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

// Solo procesar si la petición es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "shopnexs");
    if ($conexion->connect_error) {
        header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=conexion");
        exit;
    }

    // 1. Recopilación y validación de datos
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $clave = $_POST['clave'] ?? '';
    //... (aquí puedes añadir el resto de tus campos: telefono, direccion, etc.)
    
    if (empty($nombre) || empty($correo) || empty($clave)) {
        header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=vacio");
        exit;
    }

    // 2. Verificar si el correo ya existe
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

    // 3. Iniciar transacción para asegurar que todo se guarde correctamente
    $conexion->begin_transaction();

    try {
        // Hashear contraseña y generar token
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
        $token_verificacion = bin2hex(random_bytes(32));
        $rol = 'cliente';

        // Insertar en la tabla 'usuario'
        $stmt_usuario = $conexion->prepare("INSERT INTO usuario (correo_usuario, contraseña, rol, verificado, token_verificacion) VALUES (?, ?, ?, 0, ?)");
        $stmt_usuario->bind_param("ssss", $correo, $clave_hash, $rol, $token_verificacion);
        $stmt_usuario->execute();
        $id_usuario = $conexion->insert_id;
        $stmt_usuario->close();

        // Insertar en la tabla 'cliente'
        $stmt_cliente = $conexion->prepare("INSERT INTO cliente (nombre, id_usuario) VALUES (?, ?)");
        $stmt_cliente->bind_param("si", $nombre, $id_usuario);
        $stmt_cliente->execute();
        $stmt_cliente->close();

        // 4. Configurar y enviar correo de verificación
        $mail = new PHPMailer(true);
        
        // Usamos 'localhost' para evitar el error de '::1'
        $enlaceVerificacion = "http://localhost/ShopNext/ShopNext-Beta/controllers/verificarEmail.php?token=" . $token_verificacion;
        
        // Contenido del correo
        $cuerpoCorreo = "<h1>¡Bienvenido a ShopNext, " . htmlspecialchars($nombre) . "!</h1>
                         <p>Gracias por registrarte. Por favor, haz clic en el siguiente enlace para verificar tu cuenta:</p>
                         <p><a href='" . $enlaceVerificacion . "' style='padding:10px 15px; background-color:#7f56d9; color:white; text-decoration:none; border-radius:5px;'>Verificar mi cuenta</a></p>
                         <p>Si no te registraste, puedes ignorar este correo.</p>";

        // Configuración del servidor SMTP (Gmail)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shopnextnoreply@gmail.com'; // Tu correo de Gmail
        $mail->Password = 'uhym jhjw dzym pyyf'; // Tu contraseña de aplicación de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Contenido del Email
        $mail->setFrom('shopnextnoreply@gmail.com', 'ShopNext');
        $mail->addAddress($correo, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Verifica tu cuenta en ShopNext';
        $mail->Body    = $cuerpoCorreo;
        $mail->AltBody = 'Gracias por registrarte. Copia y pega este enlace en tu navegador para verificar tu cuenta: ' . $enlaceVerificacion;

        // ¡ESTA ES LA LÍNEA CORRECTA PARA ENVIAR!
        $mail->send();

        // Si todo sale bien, confirmar transacción y redirigir
        $conexion->commit();
        header("Location: ../views/auth/login.php?status=verificar_correo");
        exit;

    } catch (Exception $e) {
        // Si algo falla (inserción o envío de correo), deshacer todo
        $conexion->rollback();
        // Redirigir con un error genérico para no exponer detalles
        header("Location: /ShopNext/ShopNext-Beta/views/auth/signUp.html?error=registro_fallido");
        exit;
    }
} // Esta es la llave que faltaba o estaba mal ubicada
?>