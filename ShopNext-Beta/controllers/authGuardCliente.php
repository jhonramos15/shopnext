<?php
// controllers/authGuardCliente.php

// --- ¡AQUÍ ESTÁ LA CORRECCIÓN! ---
// Comprueba si ya existe una sesión activa antes de iniciar una nueva.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// El resto de tu guardián se queda exactamente igual.
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    // Si no es cliente, lo redirigimos al login con un mensaje.
    header("Location: ../../views/auth/login.php?error=login_required");
    exit;
}

// Manejo del tiempo de inactividad
$inactividad = 300; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactividad) {
    session_unset();
    session_destroy();
    header("Location: ../../views/auth/login.php?status=sesion_expirada");
    exit;
}
$_SESSION['last_activity'] = time(); // Refresca el tiempo de actividad
?>