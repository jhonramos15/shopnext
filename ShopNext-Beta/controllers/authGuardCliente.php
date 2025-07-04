<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: ../../views/auth/login.php");
    exit;
}

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
$_SESSION['last_activity'] = time();
?>
