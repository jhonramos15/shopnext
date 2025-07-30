<?php

namespace App\Guard;

class AuthGuard {

    /**
     * Inicia una sesión de forma segura si no existe una.
     */
    private static function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica si un usuario ya tiene una sesión activa y, de ser así,
     * lo redirige a su dashboard correspondiente.
     * * Esta función debe ser llamada al principio de las páginas
     * a las que un usuario logueado no debería acceder (como login.php o signUp.html).
     */
    public static function redirectIfAuthenticated() {
        self::startSecureSession();

        if (isset($_SESSION['id_usuario'])) {
            $rol = $_SESSION['rol'] ?? 'cliente'; // Asumir 'cliente' si no está definido

            $redirecciones = [
                'admin'    => '../dashboard/adminView.php',
                'vendedor' => '../dashboard/vendedorView.php',
                'cliente'  => '../pages/home.php' // Redirigir a la home, no a una página de usuario específica
            ];

            $destino = $redirecciones[$rol] ?? $redirecciones['cliente'];
            
            header("Location: " . $destino);
            exit;
        }
    }
}