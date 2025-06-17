<?php
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $nueva_clave = $_POST['nueva_clave'] ?? '';

    if (!$token || !$nueva_clave) {
        echo "Datos inválidos.";
        exit;
    }

    $hash = password_hash($nueva_clave, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE usuario SET contraseña = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE token_recuperacion = ?");
    $stmt->bind_param("ss", $hash, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Contraseña actualizada correctamente. Puedes <a href='login.html'>iniciar sesión</a>.";
    } else {
        echo "Error al actualizar la contraseña o token inválido.";
    }
} else {
    echo "Método no permitido.";
}
?>
