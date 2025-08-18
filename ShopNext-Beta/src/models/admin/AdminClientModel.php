<?php
// En: src/models/Admin/AdminClientModel.php

namespace App\Models\Admin;

use Config\Database;
use Exception;

class AdminClientModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            throw new Exception("No se pudo conectar a la base de datos.");
        }
    }

    /**
     * Obtiene las estadísticas de clientes para las tarjetas.
     */
    public function getClientStats(): array {
        $stats = [];

        $stats['total_usuarios'] = $this->conn->query("SELECT COUNT(*) as total FROM usuario WHERE rol = 'cliente'")->fetch_assoc()['total'] ?? 0;
        $stats['nuevos_usuarios'] = $this->conn->query("SELECT COUNT(*) as nuevos FROM usuario WHERE rol = 'cliente' AND fecha_registro >= CURDATE() - INTERVAL 7 DAY")->fetch_assoc()['nuevos'] ?? 0;

        // Cálculo del cambio porcentual
        $usuarios_anteriores = $stats['total_usuarios'] - $stats['nuevos_usuarios'];
        if ($usuarios_anteriores > 0) {
            $stats['cambio_porcentual'] = ($stats['nuevos_usuarios'] / $usuarios_anteriores) * 100;
        } elseif ($stats['nuevos_usuarios'] > 0) {
            $stats['cambio_porcentual'] = 100;
        } else {
            $stats['cambio_porcentual'] = 0;
        }

        return $stats;
    }

    /**
     * Obtiene la lista completa de todos los clientes.
     */
    public function getAllClients(): array {
        $sql = "SELECT u.id_usuario, c.nombre, u.correo_usuario, u.estado, u.fecha_registro 
                FROM usuario u 
                JOIN cliente c ON u.id_usuario = c.id_usuario 
                WHERE u.rol = 'cliente'
                ORDER BY u.fecha_registro DESC";
        
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    public function findClientById(int $id_usuario): ?array {
        $stmt = $this->conn->prepare("SELECT u.id_usuario, c.nombre, u.correo_usuario, u.estado 
                                      FROM usuario u
                                      JOIN cliente c ON u.id_usuario = c.id_usuario
                                      WHERE u.id_usuario = ? AND u.rol = 'cliente'");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $resultado;
    }

    /**
     * ✅ NUEVO: Actualiza los datos de un cliente.
     */
    public function updateClient(int $id_usuario, array $data): bool {
        // Inicia una transacción para asegurar que ambas actualizaciones se completen
        $this->conn->begin_transaction();
        try {
            // 1. Actualiza la tabla 'usuario' (correo y estado)
            $stmt1 = $this->conn->prepare("UPDATE usuario SET correo_usuario = ?, estado = ? WHERE id_usuario = ?");
            $stmt1->bind_param("ssi", $data['correo_usuario'], $data['estado'], $id_usuario);
            $stmt1->execute();

            // 2. Actualiza la tabla 'cliente' (nombre)
            $stmt2 = $this->conn->prepare("UPDATE cliente SET nombre = ? WHERE id_usuario = ?");
            $stmt2->bind_param("si", $data['nombre'], $id_usuario);
            $stmt2->execute();

            // Si todo fue bien, confirma los cambios
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Si algo falla, revierte todos los cambios
            $this->conn->rollback();
            return false;
        }
    }

    /**
     * ✅ NUEVO: Desactiva un cliente (Borrado Lógico).
     */
    public function deleteClient(int $id_usuario): bool {
        $stmt = $this->conn->prepare("UPDATE usuario SET estado = 'inactivo' WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }
}