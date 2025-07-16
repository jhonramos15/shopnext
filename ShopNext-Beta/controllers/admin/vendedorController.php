<?php
// controllers/admin/vendedorController.php
header('Content-Type: application/json');
session_start();

// Guardián de seguridad
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acceso no autorizado.']);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "shopnexs");
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos.']);
    exit;
}

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

switch ($accion) {
    case 'get_vendedor':
        $id_usuario = intval($_GET['id']);
        $stmt = $conexion->prepare("SELECT v.nombre, v.telefono, u.correo_usuario, u.estado FROM vendedor v JOIN usuario u ON v.id_usuario = u.id_usuario WHERE v.id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($vendedor = $resultado->fetch_assoc()) {
            echo json_encode(['success' => true, 'data' => $vendedor]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Vendedor no encontrado.']);
        }
        $stmt->close();
        break;

    case 'editar':
        $id_usuario = intval($_POST['id_usuario']);
        $nombre = trim($_POST['nombre']);
        $telefono = trim($_POST['telefono']);
        $correo = trim($_POST['correo']);
        $estado = trim($_POST['estado']);

        $conexion->begin_transaction();
        try {
            $stmt1 = $conexion->prepare("UPDATE usuario SET correo_usuario = ?, estado = ? WHERE id_usuario = ?");
            $stmt1->bind_param("ssi", $correo, $estado, $id_usuario);
            $stmt1->execute();

            $stmt2 = $conexion->prepare("UPDATE vendedor SET nombre = ?, telefono = ? WHERE id_usuario = ?");
            $stmt2->bind_param("ssi", $nombre, $telefono, $id_usuario);
            $stmt2->execute();

            $conexion->commit();
            echo json_encode(['success' => true, 'message' => 'Vendedor actualizado con éxito.']);
        } catch (mysqli_sql_exception $e) {
            $conexion->rollback();
            echo json_encode(['success' => false, 'error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
        break;

    case 'eliminar':
        $id_usuario = intval($_POST['id_usuario']);
        
        $conexion->begin_transaction();
        try {
            // Es importante eliminar en el orden correcto para no violar las claves foráneas
            $conexion->query("DELETE FROM vendedor WHERE id_usuario = $id_usuario");
            $conexion->query("DELETE FROM usuario WHERE id_usuario = $id_usuario");
            
            $conexion->commit();
            echo json_encode(['success' => true, 'message' => 'Vendedor eliminado con éxito.']);
        } catch (mysqli_sql_exception $e) {
            $conexion->rollback();
            echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el vendedor. Es posible que tenga productos o pedidos asociados.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción no reconocida.']);
        break;
}

$conexion->close();
?>