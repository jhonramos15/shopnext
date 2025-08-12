<?php

namespace App\Controllers\Auth;

use App\Config\Database;

class VerificarTokenController
{
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function handleVerification() {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            header('Location: index.php?action=login&error=notoken');
            exit;
        }

        $stmt = $this->db->prepare("SELECT id_usuario FROM usuario WHERE token_verificacion = ? AND verificado = 0");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            $id_usuario = $usuario['id_usuario'];

            $stmt_update = $this->db->prepare("UPDATE usuario SET verificado = 1, token_verificacion = NULL WHERE id_usuario = ?");
            $stmt_update->bind_param("i", $id_usuario);
            
            if ($stmt_update->execute() && $stmt_update->affected_rows > 0) {
                // ¡Éxito! Redirigimos al login con un mensaje de éxito.
                header('Location: index.php?action=login&status=verificado_ok');
            } else {
                header('Location: index.php?action=login&error=update_failed');
            }
        } else {
            header('Location: index.php?action=login&error=token_invalido');
        }
        
        $stmt->close();
        $this->db->close();
        exit;
    }
}