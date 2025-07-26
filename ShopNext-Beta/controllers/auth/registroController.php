<?php

// Dependencias de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluimos los archivos de PHPMailer
require_once __DIR__ . '/../../core/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../../core/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../../core/PHPMailer/src/SMTP.php';

// Incluimos el modelo de Usuario que interactúa con la BD
require_once __DIR__ . '/../../models/Usuario.php';

/**
 * Clase controladora para el proceso de registro de nuevos usuarios.
 * Sigue los principios de POO y aplica estándares de codificación.
 */
class RegistroController {
    private $usuarioModel;

    /**
     * Constructor que recibe una instancia del modelo de usuario.
     * Esto se llama Inyección de Dependencias, una buena práctica en POO.
     *
     * @param Usuario $usuarioModel Objeto para interactuar con los datos del usuario.
     */
    public function __construct(Usuario $usuarioModel) {
        $this->usuarioModel = $usuarioModel;
    }

    /**
     * Orquesta todo el proceso de registro.
     *
     * @param array $postData Datos recibidos del formulario ($_POST).
     */
    public function registrar(array $postData) {
        // 1. Recopilar y sanear datos de entrada
        $nombre   = trim($postData['nombre'] ?? '');
        $correo   = filter_var(trim($postData['correo'] ?? ''), FILTER_SANITIZE_EMAIL);
        $clave    = $postData['clave'] ?? '';
        // Aquí puedes agregar el resto de los campos: telefono, direccion, etc.
        // Ejemplo: $telefono = trim($postData['telefono'] ?? '');
        
        // 2. Validar datos
        if (empty($nombre) || empty($correo) || empty($clave)) {
            $this->redirigirConError('vacio');
        }

        if ($this->usuarioModel->correoExiste($correo)) {
            $this->redirigirConError('correo_existente');
        }

        // 3. Preparar datos para la inserción
        $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
        $token_verificacion = bin2hex(random_bytes(32));
        
        $datosUsuario = [
            'nombre' => $nombre,
            'correo' => $correo,
            'clave_hash' => $clave_hash,
            'token' => $token_verificacion,
            'rol' => 'cliente'
        ];

        // 4. Intentar registrar al usuario y enviar correo
        try {
            // Le pedimos al modelo que cree el usuario.
            // El modelo se encarga de la transacción de la BD.
            $this->usuarioModel->crearUsuario($datosUsuario);
            
            // Si el registro en BD fue exitoso, enviamos el correo.
            $this->enviarCorreoVerificacion($correo, $nombre, $token_verificacion);
            
            // Redirigimos a la página de éxito
            header("Location: " . BASE_URL . "/views/auth/login.php?status=verificar_correo");
            exit;

        } catch (Exception $e) {
            // Si algo falla (ya sea en la BD o al enviar el email),
            // redirigimos con un error genérico.
            // Opcional: registrar el error real en un log: error_log($e->getMessage());
            $this->redirigirConError('registro_fallido');
        }
    }

    /**
     * Envía el correo de verificación al nuevo usuario.
     *
     * @param string $correo Correo del destinatario.
     * @param string $nombre Nombre del destinatario.
     * @param string $token Token único para la verificación.
     */
    private function enviarCorreoVerificacion(string $correo, string $nombre, string $token) {
        $mail = new PHPMailer(true);

        $enlaceVerificacion = BASE_URL . "/controllers/verificarEmail.php?token=" . $token;
        
        $cuerpoCorreo = "<h1>¡Bienvenido a ShopNext, " . htmlspecialchars($nombre) . "!</h1>
                         <p>Gracias por registrarte. Por favor, haz clic en el siguiente enlace para verificar tu cuenta:</p>
                         <p><a href='" . $enlaceVerificacion . "' style='padding:10px 15px; background-color:#7f56d9; color:white; text-decoration:none; border-radius:5px;'>Verificar mi cuenta</a></p>
                         <p>Si no te registraste, puedes ignorar este correo.</p>";

        // Usamos las constantes del archivo config.php
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USER;
        $mail->Password   = MAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(MAIL_USER, MAIL_FROM_NAME);
        $mail->addAddress($correo, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Verifica tu cuenta en ShopNext';
        $mail->Body    = $cuerpoCorreo;
        $mail->AltBody = 'Gracias por registrarte. Copia y pega este enlace para verificar tu cuenta: ' . $enlaceVerificacion;

        $mail->send(); // Lanza una Exception si falla
    }

    /**
     * Redirige al usuario a la página de registro con un mensaje de error.
     *
     * @param string $tipoError El tipo de error a mostrar.
     */
    private function redirigirConError(string $tipoError) {
        header("Location: " . BASE_URL . "/views/auth/signUp.html?error=" . $tipoError);
        exit;
    }
}

/**
 * --- PUNTO DE ENTRADA ---
 * Esta parte del código se ejecuta cuando se llama al archivo.
 * Actúa como el "router" que instancia y ejecuta el controlador.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Incluimos las dependencias
    require_once __DIR__ . '/../../core/config.php';
    require_once __DIR__ . '/../../models/database.php';

    // 2. Creamos las instancias necesarias
    $db = Database::getConnection();           // Obtenemos la conexión a la BD
    $usuarioModel = new Usuario($db);          // Creamos el modelo de usuario con la conexión
    $controller = new RegistroController($usuarioModel); // Creamos el controlador con el modelo

    // 3. Ejecutamos el método de registro del controlador
    $controller->registrar($_POST);
}
?>