<?php
namespace App\Controllers\User;

use App\Core\SessionManager;
use App\Models\ClienteModel;
use DateTime; // Importante para la validación de la fecha

class AccountController
{
    private $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    public function show()
    {
        if (!SessionManager::isLoggedIn()) {
            header('Location: index.php?action=login');
            exit();
        }

        $id_usuario = SessionManager::get('id_usuario');
        $usuario = $this->clienteModel->findByUsuarioId($id_usuario); 

        $data = [
            'titulo_pagina' => 'Mi Cuenta',
            'usuario_logueado' => true,
            'usuario' => $usuario,
            'success_message' => SessionManager::flash('success_message'),
            'error_message' => SessionManager::flash('error_message')
        ];
        
        require_once __DIR__ . '/../../../views/pages/account.php';
    }

    /**
     * Procesa la actualización del perfil con todas las validaciones.
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !SessionManager::isLoggedIn()) {
            header('Location: index.php?action=home');
            exit();
        }

        // --- VALIDACIÓN DE EDAD ---
        $fechaNacimiento = new DateTime($_POST['fecha_nacimiento']);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimiento)->y;

        if ($edad < 15) {
            SessionManager::set('error_message', 'Debes tener al menos 15 años para registrarte.');
            header('Location: index.php?action=account');
            exit();
        }

        $id_usuario = SessionManager::get('id_usuario');
        $updateData = [
            'id_usuario'       => $id_usuario,
            'nombre'           => $_POST['nombre'] ?? '',
            'correo'           => $_POST['correo'] ?? '',
            'telefono'         => $_POST['telefono'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
            'genero'           => $_POST['genero'] ?? ''
        ];

        // --- VALIDACIÓN Y ACTUALIZACIÓN DE CONTRASEÑA (OPCIONAL) ---
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!empty($newPassword) && !empty($confirmPassword)) {
            // 1. Validar que las contraseñas nuevas coincidan
            if ($newPassword !== $confirmPassword) {
                SessionManager::set('error_message', 'Las nuevas contraseñas no coinciden.');
                header('Location: index.php?action=account');
                exit();
            }

            // 2. Validar complejidad de la nueva contraseña
            $regex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&.])[A-Za-z\d@$!%*#?&.]{7,}$/';
            if (!preg_match($regex, $newPassword)) {
                SessionManager::set('error_message', 'La contraseña debe tener mínimo 7 caracteres, un número y un símbolo (@$!%*#?&.).');
                header('Location: index.php?action=account');
                exit();
            }

            // 3. Verificar la contraseña actual antes de cambiarla
            $currentPassword = $_POST['current_password'];
            $usuarioActual = $this->clienteModel->findByUsuarioId($id_usuario);
            if (!password_verify($currentPassword, $usuarioActual['password'])) {
                SessionManager::set('error_message', 'La contraseña actual es incorrecta.');
                header('Location: index.php?action=account');
                exit();
            }

            // 4. Si todo es correcto, hasheamos la nueva contraseña y la añadimos para actualizar
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // --- LÓGICA PARA SUBIR LA FOTO DE PERFIL (RUTA CORREGIDA) ---
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['foto_perfil'];
            // Validaciones de tipo y tamaño...
            $targetDir = __DIR__ . '/../../../public/uploads/avatars/'; // ¡RUTA CORREGIDA!
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'avatar_' . uniqid() . '.' . $fileExtension;
            $targetPath = $targetDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $updateData['foto_perfil'] = $newFileName;
            }
        }

        // Llamamos al modelo para actualizar la base de datos
        if ($this->clienteModel->update($updateData)) {
            SessionManager::set('success_message', '¡Perfil actualizado con éxito!');
        } else {
            SessionManager::set('error_message', 'Hubo un error al actualizar el perfil.');
        }
        
        header('Location: index.php?action=account');
        exit();
    }
}