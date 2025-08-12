<?php

namespace App\Controllers\Auth;

use App\Services\MailerService;
use Config\Database;
use Throwable;

class RegistroController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function handleRegistration() {
        header('Content-Type: application/json');
        
        try {
            $this->db->begin_transaction();

            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $rol = $_POST['rol'] ?? 'cliente';

            if (empty($nombre) || empty($correo) || empty($password)) {
                throw new \Exception("Faltan campos esenciales.");
            }
            
            $stmt_check = $this->db->prepare("SELECT id_usuario FROM usuario WHERE correo_usuario = ?");
            $stmt_check->bind_param("s", $correo);
            $stmt_check->execute();
            if ($stmt_check->get_result()->num_rows > 0) {
                 echo json_encode(['success' => false, 'message' => 'Este correo electrónico ya está en uso.']);
                 $stmt_check->close();
                 $this->db->rollback();
                 exit;
            }
            $stmt_check->close();

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(32));

            $sql_usuario = "INSERT INTO usuario (correo_usuario, contrasena, token_verificacion, verificado) VALUES (?, ?, ?, 0)";
            $stmt_usuario = $this->db->prepare($sql_usuario);
            $stmt_usuario->bind_param("sss", $correo, $hashed_password, $token);
            $stmt_usuario->execute();
            
            $id_usuario_nuevo = $this->db->insert_id;
            $stmt_usuario->close();

            if ($rol === 'cliente') {
                $sql_perfil = "INSERT INTO cliente (nombre, telefono, direccion, genero, fecha_nacimiento, id_usuario) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_perfil = $this->db->prepare($sql_perfil);
                $stmt_perfil->bind_param("sssssi", $nombre, $_POST['telefono'], $_POST['direccion'], $_POST['genero'], $_POST['fecha_nacimiento'], $id_usuario_nuevo);
            } else {
                $sql_perfil = "INSERT INTO vendedor (nombre_vendedor, telefono_vendedor, direccion_vendedor, id_usuario) VALUES (?, ?, ?, ?)";
                $stmt_perfil = $this->db->prepare($sql_perfil);
                $stmt_perfil->bind_param("sssi", $nombre, $_POST['telefono'], $_POST['direccion'], $id_usuario_nuevo);
            }
            
            if (!$stmt_perfil->execute()) {
                throw new \Exception("Error al crear el perfil: " . $stmt_perfil->error);
            }
            $stmt_perfil->close();
            
            $mailer = new MailerService();
            if (!$mailer->sendVerificationEmail($correo, $nombre, $token)) {
                throw new \Exception("La cuenta se creó, pero falló el envío del correo de verificación.");
            }

            $this->db->commit();
            echo json_encode(['success' => true, 'message' => '¡Registro exitoso! Revisa tu correo para verificar la cuenta.']);

        } catch (Throwable $e) {
            $this->db->rollback();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Ocurrió un error inesperado.', 'error' => $e->getMessage()]);
        } finally {
            if ($this->db) $this->db->close();
            exit;
        }
    }
}