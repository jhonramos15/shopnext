<?php
// controllers/verificarEmail.php

if (isset($_GET['token'])) {
    $token_recibido = $_GET['token'];

    $conexion = new mysqli("localhost", "root", "", "shopnexs");
    if ($conexion->connect_error) {
        // En un entorno de producción, sería mejor redirigir a una página de error 500.
        die("Error de conexión a la base de datos.");
    }

    // Buscamos un usuario con ese token que aún no esté verificado.
    $stmt = $conexion->prepare("SELECT id_usuario FROM usuario WHERE token_verificacion = ? AND verificado = 0");
    $stmt->bind_param("s", $token_recibido);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        // Si se encuentra el usuario, procedemos a actualizarlo.
        $usuario = $resultado->fetch_assoc();
        $id_usuario = $usuario['id_usuario'];

        $stmt_update = $conexion->prepare("UPDATE usuario SET verificado = 1, token_verificacion = NULL WHERE id_usuario = ?");
        $stmt_update->bind_param("i", $id_usuario);
        
        // Verificamos si la actualización fue exitosa.
        if ($stmt_update->execute() && $stmt_update->affected_rows > 0) {
            // Éxito: La base de datos fue actualizada.
            header("Location: ../views/auth/login.php?status=verificado_ok");
        } else {
            // Error: No se pudo actualizar la base de datos o ya estaba verificado.
            header("Location: ../views/auth/login.php?error=update_failed");
        }
        $stmt_update->close();

    } else {
        // Si el token no es válido o la cuenta ya fue usada/verificada.
        header("Location: ../views/auth/login.php?error=token_invalido");
    }

    $stmt->close();
    $conexion->close();

} else {
    // Si no se proporciona un token en la URL, redirigir a la página principal o de login.
    header("Location: ../views/auth/login.php");
}
exit; // Asegura que el script termine después de la redirección.
?>