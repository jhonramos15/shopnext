<?php
// En src/guard/AuthGuard.php

namespace App\Guard;

// ✅ 1. Definimos la clase que envuelve todo.
class AuthGuard {

    /**
     * Inicia una sesión de forma segura si no existe una.
     */
    private static function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public static function redirectIfAuthenticated() {
        self::startSecureSession();

        if (isset($_SESSION['id_usuario'])) {
            $rol = $_SESSION['rol'] ?? 'cliente';

            // Usamos las rutas del router, no las rutas de archivos directos
            $redirecciones = [
                'admin'    => 'index.php?action=admin-dashboard',
                'vendedor' => 'index.php?action=seller-dashboard',
                'cliente'  => 'index.php?action=home' 
            ];

            $destino = $redirecciones[$rol] ?? $redirecciones['cliente'];
            
            // Usamos BASE_URL para que las rutas sean siempre correctas
            if (defined('BASE_URL')) {
                $destino = BASE_URL . $destino;
            }

            header("Location: " . $destino);
            exit;
        }
    }

    public static function redirectIfNotAuthenticated() {
        self::startSecureSession();

        // Si la sesión 'id_usuario' NO existe, lo mandamos al login.
        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?action=login"); // Redirige a la ruta de login
            exit;
        }
    }
}