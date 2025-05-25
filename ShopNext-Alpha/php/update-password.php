<?php
include 'conexion.php'; // Tu conexión a la BD

$token = $_GET['token'] ?? '';

if (!$token) {
    echo "Token no válido.";
    exit;
}

// Verificar token y que no esté expirado
$stmt = $conn->prepare("SELECT correo_usuario FROM usuario WHERE token_recuperacion = ? AND token_expiracion > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Mostrar formulario para nueva contraseña
    echo '
    <h2>Restablecer Contraseña</h2>
    <form method="POST" action="save-new-password.php">
        <input type="hidden" name="token" value="'.htmlspecialchars($token).'">
        <label>Nueva contraseña:</label><br>
        <input type="password" name="nueva_clave" required><br><br>
        <button type="submit">Cambiar Contraseña</button>
    </form>';
} else {
    echo "Token inválido o expirado.";
}
?>