<?php
use PHPMailer\PHPMailer\PHPMailer; // Dirección para darle funcionalidad al correo
use PHPMailer\PHPMailer\Exception; // Segunda dirección para darle funcionalidad al correo

require 'PHPMailer/src/Exception.php'; // Tercera dirección para darle funcionalidad al correo
require 'PHPMailer/src/PHPMailer.php'; // Cuarta dirección para darle funcionalidad al correo
require 'PHPMailer/src/SMTP.php'; // Quinta dirección para darle funcionalidad al correo

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
            $link = "http://localhost/shopnext-brayan/ShopNext-Alpha/views/auth/recoveryPassword.php?token=$token";

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
            echo "<script>alert('Correo enviado correctamente.'); window.history.back();</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error al enviar el correo.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('El correo no está registrado en la base de datos.'); window.history.back();</script>";

    }

    $stmt->close();
    $conexion->close();
}
?>
