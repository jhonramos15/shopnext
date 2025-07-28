<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    exit('No autorizado');
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    exit('Error de conexión');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'editar') {
        $id = intval($_POST['id']);
        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $correo = $_POST['correo'] ?? '';

        // Actualiza datos en cliente y usuario
        $sql = "UPDATE cliente c 
                INNER JOIN usuario u ON c.id_usuario = u.id_usuario 
                SET c.nombre = ?, c.direccion = ?, u.correo_usuario = ? 
                WHERE c.id_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $direccion, $correo, $id);
        if ($stmt->execute()) {
            echo $stmt->affected_rows > 0 ? 'ok' : 'sin cambios';
        } else {
            echo 'error_bd';
        }
        $stmt->close();

    } elseif ($accion === 'eliminar') {
        $id = intval($_POST['id']);
        $stmt = $conexion->prepare("DELETE FROM cliente WHERE id_cliente = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo $stmt->affected_rows > 0 ? 'eliminado' : 'no encontrado';
        } else {
            echo 'error_bd';
        }
        $stmt->close();
    }

    $conexion->close();
    exit;
}
