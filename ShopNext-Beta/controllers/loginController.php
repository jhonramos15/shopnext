<?php
require_once '../core/sessionManager.php';

$session = new SessionManager();

// Simulación de autenticación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user === 'admin' && $pass === '1234') {
        $session->login(1, $user);
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Credenciales inválidas.";
    }
}
?>