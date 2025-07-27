<?php
session_start();

// Verifica si está logueado y tiene rol correcto
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../../views/auth/login.html");
    exit;
}

// Tiempo máximo de inactividad (5 minutos)
$inactividad = 300;

if (isset($_SESSION['last_activity'])) {
    $tiempo_inactivo = time() - $_SESSION['last_activity'];
    if ($tiempo_inactivo > $inactividad) {
        session_unset();
        session_destroy();
        header("Location: ../../views/auth/login.php?status=sesion_expirada");
        exit;
    }
}

session_start();

// Si ya está autenticado, redirige al dashboard
if (isset($_SESSION['id_usuario']) && isset($_SESSION['rol'])) {
    // Puedes redirigir según el rol si lo deseas
    if ($_SESSION['rol'] === 'admin') {
        header("Location: ../admin/adminView.php");
    } else {
        header("Location: ../user/userView.php");
    }
    exit;
}
?>

