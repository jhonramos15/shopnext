<?php
require '../config/conexion.php'; // conexión

$token = $_POST['token'];
$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

$stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE token_recuperacion=? AND token_expiracion > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $id = $usuario['id_usuario'];

    $stmt = $conexion->prepare("UPDATE usuario SET contraseña=?, token_recuperacion=NULL, token_expiracion=NULL WHERE id_usuario=?");
    $stmt->bind_param("si", $clave, $id);
    $stmt->execute();

    echo "<script>alert('Contraseña actualizada correctamente.'); window.location.href='../views/auth/login.html';</script>";

} else {
    echo "Token inválido o expirado.";
}
?>
