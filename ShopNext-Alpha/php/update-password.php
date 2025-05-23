<?php
include 'conexion.php';

$token = $_POST['token'];
$nueva_clave = password_hash($_POST['nueva_clave'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE usuarios SET clave = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE token_recuperacion = ?");
$stmt->bind_param("ss", $nueva_clave, $token);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Contraseña actualizada correctamente.";
} else {
    echo "Error al actualizar la contraseña.";
}
?>
