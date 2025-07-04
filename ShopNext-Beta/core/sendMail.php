<?php
use PHPMailer\PHPMailer\PHPMailer; // Dirección para darle funcionalidad al correo
use PHPMailer\PHPMailer\Exception; // Segunda dirección para darle funcionalidad al correo

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "shopnexs"); 
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verificar si el correo existe en la base de datos
    $stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        // Crear el token
            $token = bin2hex(random_bytes(16));
            $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Guardar el token en la base de datos
            $stmt = $conexion->prepare("UPDATE usuario SET token_recuperacion=?, token_expiracion=? WHERE correo_usuario=?");
            $stmt->bind_param("sss", $token, $expira, $email);
            $stmt->execute();

        // Crear el link con el token
            $link = "http://localhost/shopnext/ShopNext-Beta/views/auth/recoveryPassword.php?token=$token";

        // Crear la instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Hosting
            $mail->SMTPAuth = true;
            $mail->Username = 'shopnextnoreply@gmail.com'; // Correo que enviará las claves
            $mail->Password = 'uhym jhjw dzym pyyf'; // Contraseña Google generada
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587; // Puerto (no tocar)

            $mail->setFrom('shopnextnoreply@gmail.com', 'ShopNexs'); // Correo que envía los tokens
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = '¡Recupera tu clave en ShopNext!';
            $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='$link'>$link</a><br>Este enlace expirará en 1 hora.";

            $mail->send();
                header("Location: ../views/auth/forgotPassword.html?status=correo_enviado");
            exit;

        } catch (Exception $e) {
        header("Location: ../views/auth/forgotPassword.html?status=error_envio");
        exit;
        }
    } else {
        header("Location: ../views/auth/forgotPassword.html?status=no_existe");
        exit;

    }

    $stmt->close();
    $conexion->close();
}
?>
