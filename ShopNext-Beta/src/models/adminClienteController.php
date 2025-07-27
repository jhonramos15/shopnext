<?php
// controllers/admin/AdminController.php

session_start();
// Aquí podrías validar que solo los administradores puedan acceder
// if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
//     header("Location: /ruta-a-tu-login");
//     exit;
// }

require_once __DIR__ . '/../../models/Database.php';
require_once __DIR__ . '/../../models/Usuario.php';

// Verificamos que se haya enviado una acción
if (!isset($_POST['action'])) {
    header("Location: /ruta-a-tu-dashboard?error=no_action");
    exit;
}

$db = Database::getConnection();
$usuarioModel = new Usuario($db);
$action = $_POST['action'];

switch ($action) {
    case 'crear_usuario':
        $datos = [
            'nombre' => $_POST['nombre'],
            'correo_usuario' => $_POST['correo_usuario'],
            'clave' => $_POST['clave'],
            'telefono' => $_POST['telefono'],
            'direccion' => $_POST['direccion'],
            'rol' => $_POST['rol'],
            'estado' => $_POST['estado']
        ];
        
        if ($usuarioModel->crearPorAdmin($datos)) {
            header("Location: ../../views/admin/dashboard_clientes.php?success=creado");
        } else {
            header("Location: ../../views/admin/dashboard_clientes.php?error=crear");
        }
        exit;

    case 'actualizar_usuario':
        $datos = [
            'id_usuario' => $_POST['id_usuario'],
            'nombre' => $_POST['nombre'],
            'correo_usuario' => $_POST['correo_usuario'],
            'telefono' => $_POST['telefono'],
            'direccion' => $_POST['direccion'],
            'rol' => $_POST['rol'],
            'estado' => $_POST['estado']
        ];

        if ($usuarioModel->actualizarPorAdmin($datos)) {
            header("Location: ../../views/admin/dashboard_clientes.php?success=actualizado");
        } else {
            header("Location: ../../views/admin/dashboard_clientes.php?error=actualizar");
        }
        exit;
    
    // Aquí podrías añadir un caso para 'eliminar_usuario'
}
?>