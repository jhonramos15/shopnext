<?php
namespace App\Core;

class SessionManager {
    
    /**
     * Inicia la sesión de forma segura, solo si no hay una activa.
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Guarda un valor en la sesión.
     * @param string $key La clave para el dato.
     * @param mixed $value El valor a guardar.
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Obtiene un valor de la sesión.
     * @param string $key La clave del dato.
     * @param mixed $default El valor a devolver si la clave no existe.
     * @return mixed El valor de la sesión o el valor por defecto.
     */
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verifica si una clave existe en la sesión.
     * @param string $key La clave a verificar.
     * @return bool
     */
    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina una clave específica de la sesión.
     * @param string $key La clave a eliminar.
     */
    public static function remove($key) {
        unset($_SESSION[$key]);
    }
    
    /**
     * Destruye la sesión por completo.
     * Es el método de logout.
     */
    public static function destroy() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    /**
     * Verifica si el usuario ha iniciado sesión.
     * (Basado en la existencia de una clave 'id_usuario')
     * @return bool
     */
    public static function isLoggedIn() {
        return self::has('id_usuario');
    }

    /**
     * Verifica si la sesión ha expirado por inactividad.
     * NO redirige, solo devuelve true o false.
     * @param int $timeout Tiempo de vida en segundos (ej. 900 = 15 min).
     * @return bool True si la sesión ha expirado, false en caso contrario.
     */
    public static function hasExpired($timeout = 900) {
        if (!self::has('last_activity')) {
            // Si no hay registro de actividad, no ha expirado.
            return false;
        }

        $isExpired = (time() - self::get('last_activity')) > $timeout;

        if ($isExpired) {
            self::destroy(); // Si expiró, la destruimos.
            return true;
        }
        
        // Si no ha expirado, renovamos la actividad.
        self::set('last_activity', time());
        return false;
    }
}