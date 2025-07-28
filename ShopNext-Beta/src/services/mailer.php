<?php
namespace App\Services; // Usaremos namespaces para organizar

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        // Configuración del servidor SMTP (usando constantes de tu config.php)
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.example.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'user@example.com';
        $this->mailer->Password = 'secret';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
    }

    public function sendVerificationEmail(string $recipientEmail, string $token) {
        try {
            $this->mailer->setFrom('no-reply@shopnexs.com', 'ShopNexs');
            $this->mailer->addAddress($recipientEmail);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Verifica tu cuenta en ShopNexs';
            $verificationLink = "http://localhost/ShopNext-Beta/verify-email?token=" . $token;
            $this->mailer->Body    = "Gracias por registrarte. Por favor, haz clic en el siguiente enlace para verificar tu cuenta: <a href='{$verificationLink}'>Verificar Cuenta</a>";
            $this->mailer->AltBody = "Gracias por registrarte. Copia y pega el siguiente enlace en tu navegador para verificar tu cuenta: {$verificationLink}";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Podrías registrar el error: error_log("Mailer Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}