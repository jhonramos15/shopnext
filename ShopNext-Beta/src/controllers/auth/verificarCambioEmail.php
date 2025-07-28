<?php
// controllers/verificarCambioEmail.php
if (isset($_GET['token'])) {
    $token_recibido = $_GET['token'];

    $conexion = new mysqli("localhost", "root", "", "shopnexs");
    if ($conexion->connect_error) { die("Error de conexión."); }

    // Buscamos al usuario que tiene este token de cambio
    $stmt = $conexion->prepare("SELECT id_usuario, nuevo_correo FROM usuario WHERE token_cambio_correo = ?");
    $stmt->bind_param("s", $token_recibido);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        // Si lo encontramos, actualizamos el correo principal
        $usuario = $resultado->fetch_assoc();
        $id_usuario = $usuario['id_usuario'];
        $nuevo_correo = $usuario['nuevo_correo'];

        // Actualizamos el correo y limpiamos los campos temporales
        $stmt_update = $conexion->prepare("UPDATE usuario SET correo_usuario = ?, nuevo_correo = NULL, token_cambio_correo = NULL WHERE id_usuario = ?");
        $stmt_update->bind_param("si", $nuevo_correo, $id_usuario);
        
        if ($stmt_update->execute()) {
            // Mandamos al login con un mensaje de éxito. Es buena práctica hacer que inicie sesión de nuevo.
            header("Location: ../views/auth/login.php?status=correo_cambiado_ok");
        } else {
            header("Location: ../views/auth/login.php?error=update_failed");
        }
    } else {
        header("Location: ../views/auth/login.php?error=token_invalido");
    }
    $conexion->close();
}
exit;
?>