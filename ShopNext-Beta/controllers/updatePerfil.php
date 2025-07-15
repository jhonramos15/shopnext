<?php
// controllers/updatePerfil.php (Versión con Lógica Condicional)
session_start();
require_once __DIR__ . '/authGuardCliente.php';

$conexion = new mysqli("localhost", "root", "", "shopnexs");
$id_usuario_actual = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- RECOPILACIÓN DE DATOS ---
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $genero = $_POST['genero'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // --- 1. VALIDACIÓN DE CAMPOS PRINCIPALES ---
    if (strlen($nombre) === 0 || strlen($correo) === 0) {
        header("Location: ../views/pages/account.php?error=campos_vacios_perfil");
        exit;
    }

    // --- 2. LÓGICA PARA ACTUALIZAR DATOS DEL PERFIL (siempre se ejecuta) ---
    // (Manejo de la foto de perfil)
    $stmt_foto = $conexion->prepare("SELECT foto_perfil FROM cliente WHERE id_usuario = ?");
    $stmt_foto->bind_param("i", $id_usuario_actual);
    $stmt_foto->execute();
    $nombre_foto_actual = $stmt_foto->get_result()->fetch_assoc()['foto_perfil'];
    $nombre_foto_para_db = $nombre_foto_actual;

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        // ... (lógica para subir la nueva foto) ...
    }

    // Actualizamos las tablas usuario y cliente
    $stmt_user = $conexion->prepare("UPDATE usuario SET correo_usuario = ? WHERE id_usuario = ?");
    $stmt_user->bind_param("si", $correo, $id_usuario_actual);
    $stmt_user->execute();
    
    $stmt_cliente = $conexion->prepare("UPDATE cliente SET nombre = ?, telefono = ?, genero = ?, fecha_nacimiento = ?, foto_perfil = ? WHERE id_usuario = ?");
    $stmt_cliente->bind_param("sssssi", $nombre, $telefono, $genero, $fecha_nacimiento, $nombre_foto_para_db, $id_usuario_actual);
    $stmt_cliente->execute();

    // --- 3. LÓGICA PARA CAMBIAR LA CONTRASEÑA (SÓLO SI SE INTENTA) ---
    // Este bloque solo se ejecuta si al menos uno de los campos de contraseña se ha rellenado
    if (!empty($current_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        
        // Si se intenta cambiar, los tres campos son obligatorios
        if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
             header("Location: ../views/pages/account.php?error=pass_campos_incompletos");
             exit;
        }

        if ($new_pass !== $confirm_pass) {
            header("Location: ../views/pages/account.php?error=pass_no_coincide");
            exit;
        }
        
        $stmt_pass = $conexion->prepare("SELECT contraseña FROM usuario WHERE id_usuario = ?");
        $stmt_pass->bind_param("i", $id_usuario_actual);
        $stmt_pass->execute();
        $hash_actual = $stmt_pass->get_result()->fetch_assoc()['contraseña'];
        
        if (password_verify($current_pass, $hash_actual)) {
            // ¡VALIDACIÓN NUEVA! Comprueba si la nueva contraseña es igual a la actual
            if ($current_pass === $new_pass) {
                header("Location: ../views/pages/account.php?error=pass_igual_actual");
                exit;
            }

            // Si todo está bien, actualizamos a la nueva contraseña
            $nuevo_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt_update_pass = $conexion->prepare("UPDATE usuario SET contraseña = ? WHERE id_usuario = ?");
            $stmt_update_pass->bind_param("si", $nuevo_hash, $id_usuario_actual);
            $stmt_update_pass->execute();
        } else {
            header("Location: ../views/pages/account.php?error=pass_incorrecta");
            exit;
        }
    }
    
    // 4. Si todo ha ido bien (con o sin cambio de contraseña), redirigimos con éxito
    header("Location: ../views/pages/account.php?status=ok");
    exit;
}
?>