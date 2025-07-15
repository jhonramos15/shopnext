<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['estado'];
    $id_vendedor_session = $_SESSION['id_vendedor'];

    $conexion = new mysqli("localhost", "root", "", "shopnexs");
    
    // Verificamos que el pedido pertenezca al vendedor por seguridad
    $stmt = $conexion->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ? AND id_vendedor = ?");
    $stmt->bind_param("sii", $nuevo_estado, $id_pedido, $id_vendedor_session);
    $stmt->execute();

    header("Location: /shopnext/ShopNext-Beta/views/dashboard/vendedor/pedidos.php?status=success");
    exit;
}
?>