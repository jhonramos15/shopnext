<?php
// controllers/user/crearTicket.php
session_start();

// Guardián para asegurar que el usuario esté logueado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: ../../views/auth/login.php");
    exit;
}

// Verificar que se recibieron los datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recopilar datos del formulario
    $id_usuario = $_SESSION['id_usuario'];
    $asunto = trim($_POST['asunto'] ?? '');
    $prioridad = trim($_POST['prioridad'] ?? 'Baja');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // Validación simple
    if (empty($asunto) || empty($mensaje)) {
        header("Location: ../../views/user/pages/contact.php?error=vacio");
        exit;
    }

    // Conexión a la BD
    $conexion = new mysqli("localhost", "root", "", "shopnexs");
    if ($conexion->connect_error) {
        header("Location: ../../views/user/pages/contact.php?error=conexion");
        exit;
    }

    // Insertar el nuevo ticket en la base de datos
    $stmt = $conexion->prepare("INSERT INTO tickets (id_usuario, asunto, mensaje, prioridad) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id_usuario, $asunto, $mensaje, $prioridad);

    if ($stmt->execute()) {
        // CORREGIDO: Redirigir a la ruta correcta que incluye /user/
        header("Location: ../../views/user/pages/contact.php?status=ticket_enviado");
    } else {
        // CORREGIDO: Redirigir a la ruta correcta que incluye /user/
        header("Location: ../../views/user/pages/contact.php?error=db_error");
    }

    $stmt->close();
    $conexion->close();

} else {
    // Si no es una petición POST, redirigir
    header("Location: ../../views/user/pages/contact.php");
    exit;
}
?>