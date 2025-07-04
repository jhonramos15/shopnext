<?php
// controllers/logout.php

session_start(); // Inicia la sesión para poder acceder a ella

// Destruye todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, borra también la cookie de sesión.
// Nota: ¡Esto destruirá la sesión, y no solo los datos de la sesión!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruye la sesión.
session_destroy();

// --- ¡EL CAMBIO CLAVE ESTÁ AQUÍ! ---
// Redirige a la página de login con el parámetro para la alerta
header("Location: ../views/auth/login.php?status=logout_ok");
exit(); // Es importante terminar el script después de una redirección

?>