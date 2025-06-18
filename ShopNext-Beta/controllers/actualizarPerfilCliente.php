<?php
require_once '../config/conexion_pdo.php';
require_once '../models/usuario.php';

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$correo = trim($_POST['email'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$direccion = trim($_POST['address'] ?? '');
$old_pass = $_POST['old_password'] ?? '';
$new_pass = $_POST['new_password'] ?? '';
$confirm_pass = $_POST['confirm_password'] ?? '';

// Verifica que haya al menos un cambio
if ($correo === '' && $nombre === '' && $direccion === '' && $new_pass === '') {
    header("Location: ../views/pages/account.php?status=sin_cambios");
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$usuario = new Usuario($conn);
$usuario->id_usuario = $id_usuario;

// Validar y asignar solo si el correo se va a cambiar
if ($correo !== '') {
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../views/pages/account.php?status=correo_invalido");
    exit;
    }
    $usuario->correo = $correo;
}

// Validar y asignar nombre si lo va a cambiar
if ($nombre !== '') {
    if (strlen($nombre) < 7) {
    header("Location: ../views/pages/account.php?status=nombre_invalido");
    exit;
    }
    $usuario->nombre = $nombre;
}

// Validar y asignar dirección si la va a cambiar
if ($direccion !== '') {
    if (strlen($direccion) < 10) {
        header("Location: ../views/pages/account.php?status=datos_invalidos");
    exit;
    }
    $usuario->direccion = $direccion;
}

// Verificar contraseña actual si quiere cambiarla
if (!empty($new_pass)) {
    $stmt = $conn->prepare("SELECT contraseña FROM usuario WHERE id_usuario = :id");
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $passActualBD = $row['contraseña'] ?? '';

    if (!password_verify($old_pass, $passActualBD)) {
        header("Location: ../views/pages/account.php?status=pass_incorrecta");
    exit;
    }

    if ($new_pass !== $confirm_pass) {
        header("Location: ../views/pages/account.php?status=pass_no_coincide");
    exit;
    }

    $usuario->password = $new_pass;
}

// Ejecutar actualización
if ($usuario->actualizarPerfil()) {
    header("Location: ../views/pages/account.php?status=ok");
    exit;
} else {
    header("Location: ../views/pages/account.php?status=error");
    exit;
}
?>
