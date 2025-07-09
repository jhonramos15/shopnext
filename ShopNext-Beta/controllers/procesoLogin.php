<?php

session_start();
$conexion = new mysqli("localhost", "root", "", "shopnexs");

if ($conexion->connect_error) {
    header("Location: ../views/auth/login.php?error=conexion");
    exit;
}

$correo = $_POST['correo'] ?? '';
$clave = $_POST['password'] ?? '';

if (empty($correo) || empty($clave)) {
    header("Location: ../views/auth/login.php?error=vacio");
    exit;
}

// 1. Buscamos al usuario por su correo
$stmt = $conexion->prepare("SELECT id_usuario, contraseña, rol, verificado FROM usuario WHERE correo_usuario = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    
    // 2. Verificamos la contraseña
    if (password_verify($clave, $usuario['contraseña'])) {
        
        // --- ¡LA CORRECCIÓN CLAVE ESTÁ AQUÍ! ---
        // 3. Verificamos si la columna 'verificado' es igual a 0
        if ($usuario['verificado'] == 0) {
            header("Location: ../views/auth/login.php?error=no_verificado");
            exit;
        }

        // 4. Si todo está bien, creamos la sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['last_activity'] = time();

        // 5. Redirigimos según el rol
        if ($usuario['rol'] === 'admin') {
            header("Location: ../views/dashboard/adminView.php");
        } elseif ($usuario['rol'] === 'vendedor') {
            header("Location: ../views/dashboard/vendedorView.php");
        } else {
            header("Location: ../views/user/indexUser.php");
        }
        exit;

    } else {
        // Contraseña incorrecta
        header("Location: ../views/auth/login.php?error=clave");
        exit;
    }
} else {
    // Usuario no encontrado
    header("Location: ../views/auth/login.php?error=usuario");
    exit;
}

$stmt->close();
$conexion->close();
?>