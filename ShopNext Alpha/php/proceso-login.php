<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$correo = $_POST['correo'] ?? '';
$clave = $_POST['clave'] ?? '';

$stmt = $conexion->prepare("SELECT id_usuario, contraseña, rol FROM usuario WHERE correo_usuario = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($id_usuario, $hash_password, $rol);
    $stmt->fetch();

    if (password_verify($clave, $hash_password)) {
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['correo'] = $correo;
        $_SESSION['rol'] = $rol;

        $stmt->close();
        $conexion->close();

        if ($rol === 'admin') {
            header("Location: ../html/dashboard-admin.php");
        } else if ($rol === 'cliente') {
            header("Location: ../html/index.html");
        } else if ($rol === 'vendedor') {
            header("Location: ../html/index.html");
        } else {
            header("Location: dashboard_cliente.php");
        }
        exit;
    } else {
        $stmt->close();
        $conexion->close();
        header("Location: ../html/login.html?error=clave");
        exit;
    }
} else {
    $stmt->close();
    $conexion->close();
    header("Location: ../html/login.html?error=usuario");
    exit;
}
