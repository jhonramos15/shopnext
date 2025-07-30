<?php

namespace App\Controllers\Auth;

use App\Models\Usuario;

class LoginController {
    
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Muestra el formulario de inicio de sesión.
     * Esta función es llamada por el router cuando la petición es GET.
     */
    public function showLoginForm() {
        // Asegúrate de que init.php ya se haya cargado para tener BASE_URL
        require_once __DIR__ . '/../../../views/auth/login.php';
    }

    /**
     * Procesa los datos del formulario de login.
     * Esta función es llamada por el router cuando la petición es POST.
     */
public function handleLogin() {
    // Renombramos tu método 'procesar' a 'handleLogin' para más claridad
    $this->iniciarSesionSegura();

    $correo = $_POST['correo'] ?? '';
    $clave = $_POST['password'] ?? '';

    if (empty($correo) || empty($clave)) {
        $this->redirigirConError('vacio');
    }

    // Llamamos al modelo para que intente autenticar
    $usuario = $this->usuarioModel->autenticarUsuario($correo, $clave);

    // El resto del código no se ejecutará por ahora, lo cual es correcto para depurar.
    if (!$usuario) {
        $this->redirigirConError('credenciales');
    }

    if ($usuario['verificado'] == 0) {
        $this->redirigirConError('no_verificado');
    }

    $this->establecerSesion($usuario);
    $this->redirigirPorRol($usuario['rol']);
}

    /**
     * NUEVO MÉTODO: Cierra la sesión del usuario.
     * Esto soluciona el error de la pantalla en blanco.
     */
    public function logout() {
        // Inicia la sesión solo para poder destruirla
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Limpia todas las variables de sesión
        $_SESSION = [];

        // Destruye la sesión completamente
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        // Redirige a la página de inicio (CON LA CORRECCIÓN)
        header('Location: index.php?action=login&status=logged_out');
        // ¡MUY IMPORTANTE! Detiene el script para asegurar la redirección.
        exit();
    }


    private function iniciarSesionSegura() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function establecerSesion(array $usuario) {
        session_regenerate_id(true);
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['last_activity'] = time();
    }

    private function redirigirConError(string $tipoError) {
        // Redirige usando BASE_URL para que sea consistente (CON LA CORRECCIÓN)
        header("Location: " . \BASE_URL . "login?error=" . $tipoError);
        exit;
    }

private function redirigirPorRol(string $rol) {
    // El destino por defecto es el homepage.
    $destino = 'index.php?action=home';

    if ($rol === 'admin') {
        // Un admin va a su dashboard.
        $destino = 'index.php?action=admin-dashboard'; 
    } elseif ($rol === 'vendedor') {
        // Un vendedor va a su dashboard.
        $destino = 'index.php?action=seller-dashboard';
    }
    // Para cualquier otro rol (como 'cliente'), no hacemos nada
    // y se usará el destino por defecto: 'index.php?action=home'.
    
    header("Location: " . $destino);
    exit;
}
}