<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    
    // Tu cuenta de Gmail
    $mail->Username = 'shopnextsoporte@gmail.com';
    $mail->Password = 'mxxw vncl jixh ekco'; // Genera un password de aplicación en tu cuenta Google
    
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Remitente y destinatario
    $mail->setFrom('tu-email@gmail.com', 'ShopNexs');
    $mail->addAddress($email); // $email es el correo del usuario que quieres enviar

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Recupera tu contraseña en ShopNext';
    $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='$link'>$link</a>";

    $mail->send();
    echo "Correo enviado correctamente.";
} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
}
?>
