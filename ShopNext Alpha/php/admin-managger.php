<?php
session_start();

// Validar que el usuario está logueado y es admin
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../html/login.html"); // Redirige a login si no es admin
    exit;
}
?>