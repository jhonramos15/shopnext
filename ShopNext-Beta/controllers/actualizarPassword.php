<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nueva_clave = password_hash($_POST['nueva_clave'], PASSWORD_DEFAULT);

    // Actualiza contraseña y elimina token
    $stmt = $conexion->prepare("UPDATE usuario SET contraseña = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE token_recuperacion = ?");
    $stmt->bind_param("ss", $nueva_clave, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Contraseña actualizada correctamente. <a href='../views/auth/login.php'>Iniciar sesión</a>";
    } else {
        echo "Error al actualizar la contraseña. Intenta nuevamente.";
    }
}
?>
