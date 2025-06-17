<?php
session_start();
require_once "../config/conexion.php"; // si tienes una conexión centralizada

$correo = $_POST['correo'] ?? '';
$clave = $_POST['clave'] ?? '';

if (empty($correo) || empty($clave)) {
    header("Location: ../views/auth/login.php?error=vacio");
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$stmt = $conexion->prepare("SELECT id_usuario, contraseña, rol FROM usuario WHERE correo_usuario = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id_usuario, $hash_password, $rol);
    $stmt->fetch();

    if (password_verify($clave, $hash_password)) {
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['correo'] = $correo;
        $_SESSION['rol'] = $rol;

        if ($rol === 'admin') {
            header("Location: ../views/dashboard/adminView.php");
        } elseif ($rol === 'cliente') {
            header("Location: ../views../user/indexUser.php");
        } elseif ($rol === 'vendedor') {
            header("Location: ../views/dashboard/vendedorView.php");
        } else {
            header("Location: ../public/index.html");
        }
        exit;
    } else {
        header("Location: ../views/auth/login.php?error=clave");
        exit;
    }
} else {
    header("Location: ../views/auth/login.php?error=usuario");
    exit;
}
