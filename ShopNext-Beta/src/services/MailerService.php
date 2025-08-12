<?php

namespace App\Services;

require_once __DIR__ . '/../core/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../core/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../core/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    private function configure() {
        // Configuración del servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->SMTPDebug = 0;
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = 'shopnextsoporte@gmail.com';
        $this->mailer->Password   = 'otggvxfhagmgffkv';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port       = 465;
        $this->mailer->CharSet    = 'UTF-8';

        // Emisor del correo
        $this->mailer->setFrom('shopnextsoporte@gmail.com', 'ShopNext Test');
    }

    /**
     * Envía el correo de verificación de cuenta.
     * @param string $recipientEmail El email del destinatario.
     * @param string $recipientName El nombre del destinatario.
     * @param string $token El token de verificación.
     * @return bool True si el correo se envió, false si no.
     */
    public function sendVerificationEmail(string $recipientEmail, string $recipientName, string $token): bool {
        try {
            $this->mailer->addAddress($recipientEmail, $recipientName);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Verifica tu cuenta en ShopNext';
            
            // Asegúrate de que este enlace apunte a tu script de verificación
            $verificationLink = "http://localhost/shopnext/public/index.php?action=verify&token=" . $token;

            $this->mailer->Body = $this->getHtmlTemplate($recipientName, $verificationLink);
            $this->mailer->AltBody = "Para verificar tu cuenta, copia y pega este enlace en tu navegador: " . $verificationLink;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }

    private function getHtmlTemplate(string $name, string $link): string {
        return "
            <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <h2 style='color: #DB4444;'>¡Bienvenido a ShopNext, {$name}!</h2>
                <p>Gracias por registrarte. Solo falta un paso para activar tu cuenta.</p>
                <p>Por favor, haz clic en el siguiente botón para verificar tu dirección de correo electrónico:</p>
                <a href='{$link}' style='background-color: #DB4444; color: white; padding: 15px 25px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px; font-size: 16px;'>Verificar Mi Cuenta</a>
                <p>Si el botón no funciona, puedes copiar y pegar el siguiente enlace en tu navegador:</p>
                <p><a href='{$link}' style='color: #DB4444;'>{$link}</a></p>
                <hr>
                <p style='font-size: 0.9em; color: #777;'>Si no te registraste en ShopNext, por favor ignora este correo.</p>
            </div>
        ";
    }
}
