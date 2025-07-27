<?php
// Establece la respuesta como JSON
header('Content-Type: application/json');

// Incluye los archivos necesarios (configuración, inicialización, modelo)
require_once __DIR__ . '/../../core/init.php';
require_once __DIR__ . '/../../models/usuario.php';

/**
 * Clase RegistroController
 * Maneja la lógica para registrar tanto a clientes como a vendedores.
 * Está diseñada para ser el único punto de entrada para las solicitudes de registro.
 */
class RegistroController {
    private $usuarioModel;

    /**
     * Constructor de la clase.
     * Inicializa el modelo de usuario para interactuar con la base de datos.
     */
    public function __construct() {
        // La conexión a la BD se gestiona dentro de la clase Usuario,
        // manteniendo el controlador limpio de lógica de conexión.
        $this->usuarioModel = new Usuario();
    }

    /**
     * Método principal que procesa la solicitud de registro.
     * Determina el tipo de usuario y llama al método de registro correspondiente.
     */
    public function procesarRegistro() {
        // Solo procesar si la solicitud es de tipo POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->enviarRespuesta(false, 'Método de solicitud no válido.');
            return;
        }

        // Determinar el tipo de usuario a registrar desde el formulario
        $userType = $_POST['user_type'] ?? '';

        switch ($userType) {
            case 'cliente':
                $this->registrarCliente();
                break;
            case 'vendedor':
                $this->registrarVendedor();
                break;
            default:
                $this->enviarRespuesta(false, 'Tipo de usuario no especificado o inválido.');
                break;
        }
    }

    /**
     * Maneja la lógica de validación y registro para un nuevo vendedor.
     */
    private function registrarVendedor() {
        // 1. Recoger datos del POST
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $password = $_POST['password'] ?? '';

        // 2. Validar datos (backend)
        $errores = [];
        if (!preg_match("/^[A-Za-z\s]{5,35}$/", $nombre)) {
            $errores['nombre'] = 'El nombre debe tener entre 5 y 35 caracteres alfabéticos.';
        }
        if (!preg_match("/^\d{10}$/", $telefono)) {
            $errores['telefono'] = 'El teléfono debe tener 10 dígitos numéricos.';
        }
        if (empty($direccion)) {
            $errores['direccion'] = 'La dirección es obligatoria.';
        }
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{7,30}$/", $password)) {
            $errores['password'] = 'La contraseña no cumple los requisitos de seguridad.';
        }
        
        // Comprobar si el teléfono ya existe
        if ($this->usuarioModel->telefonoExiste($telefono)) {
            $errores['telefono'] = 'Este número de teléfono ya está registrado.';
        }

        if (!empty($errores)) {
            $this->enviarRespuesta(false, 'Por favor, corrige los errores.', $errores);
            return;
        }

        // 3. Llamar al modelo para crear el vendedor
        // (Asumiendo que tienes un método 'crearVendedor' en tu clase Usuario)
        $resultado = $this->usuarioModel->crearVendedor($nombre, $telefono, $direccion, $password);

        if ($resultado) {
            $this->enviarRespuesta(true, '¡Vendedor registrado con éxito!');
        } else {
            $this->enviarRespuesta(false, 'Hubo un error al registrar al vendedor. Inténtalo de nuevo.');
        }
    }
    
    /**
     * Maneja la lógica de validación y registro para un nuevo cliente.
     * (Esta es la lógica que ya tenías, ahora encapsulada en un método)
     */
    private function registrarCliente() {
        // Implementa aquí la lógica de validación y registro para el cliente.
        // Ejemplo:
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        // ... otros campos del cliente

        $errores = [];
        // ... tus validaciones para cliente
        if (empty($nombre)) {
            $errores['nombre'] = 'El nombre del cliente es obligatorio.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El email no es válido.';
        }
        if ($this->usuarioModel->emailExiste($email)) {
             $errores['email'] = 'Este email ya está en uso.';
        }

        if (!empty($errores)) {
            $this->enviarRespuesta(false, 'Por favor, corrige los errores.', $errores);
            return;
        }

        // Llamar al modelo para crear el cliente
        // $resultado = $this->usuarioModel->crearCliente(...);
        // ...
        
        // Simulación de éxito
        $this->enviarRespuesta(true, '¡Cliente registrado con éxito!');
    }


    /**
     * Envía una respuesta JSON estandarizada y finaliza la ejecución del script.
     * @param bool $success - True si la operación fue exitosa, false si no.
     * @param string $message - Un mensaje descriptivo del resultado.
     * @param array $errors - (Opcional) Un array asociativo con errores específicos por campo.
     */
    private function enviarRespuesta(bool $success, string $message, array $errors = []) {
        $response = ['success' => $success, 'message' => $message];
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        echo json_encode($response);
        exit();
    }
}

// --- Punto de entrada del script ---
// Se crea una instancia del controlador y se invoca el método principal.
$controlador = new RegistroController();
$controlador->procesarRegistro();