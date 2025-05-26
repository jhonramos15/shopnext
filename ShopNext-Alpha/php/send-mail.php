<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Obtener el correo del formulario
$email = $_POST['email']; // Asegúrate de que el campo del formulario se llame "email"
$link = $_POST['link'];   // Supongo que también estás enviando el link para restablecer

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    
    $mail->Username = 'shopnextnoreply@gmail.com'; // Correo que mandará los mensajes
    $mail->Password = 'uhym jhjw dzym pyyf'; // Contraseña aplicación Google

    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Remitente y destinatario
    $mail->setFrom('tu-email@gmail.com', 'ShopNexs');
    $mail->addAddress($email); // Ahora $email está definido

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Recupera tu clave en ShopNext';
    $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña, si no solicitaste el código, haz caso omiso. <a href='$link'>$link</a>";

    $mail->send();
    echo "Correo enviado correctamente.";
} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
}
?>
