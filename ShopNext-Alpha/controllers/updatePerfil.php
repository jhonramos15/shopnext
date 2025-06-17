<?php
session_start();
require_once 'includes/../config/conexion.php';
require_once 'classes/../classes/usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->getConnection();
    $usuario = new Usuario($db);

    $usuario->id_usuario = $_SESSION['id_usuario']; // Asumiendo que estás logueado
    $usuario->correo = $_POST['correo'] ?? '';
    $usuario->password = $_POST['nueva_contrasena'] ?? '';
    $usuario->nombre = $_POST['nombre'] ?? '';
    $usuario->direccion = $_POST['direccion'] ?? '';

    if ($usuario->actualizarPerfil()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
