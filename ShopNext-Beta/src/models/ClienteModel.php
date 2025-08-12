<?php
// src/models/ClienteModel.php
namespace App\Models;

use Config\Database;
use Exception; // Importante para capturar errores en la transacción

/**
 * Modelo para obtener información de los clientes, manejando datos de las tablas 'cliente' y 'usuarios'.
 */
class ClienteModel {
    private $conn;

    public function __construct() {
        $database = new \Config\Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Busca los datos combinados de un cliente y su usuario a partir de su ID de usuario.
     * Utiliza un JOIN para obtener datos de ambas tablas en una sola consulta.
     *
     * @param int $id_usuario El ID del usuario asociado al cliente.
     * @return array|null Los datos combinados o null si no se encuentra.
     */
    public function findByUsuarioId(int $id_usuario): ?array {
        // La consulta SQL ahora une la tabla 'cliente' con la tabla 'usuarios'.
        $sql = "SELECT 
                    c.id_cliente, c.nombre, c.telefono, c.fecha_nacimiento, c.genero, c.foto_perfil,
                    u.correo_usuario, u.id_usuario
                FROM cliente c
                JOIN usuario u ON c.id_usuario = u.id_usuario
                WHERE c.id_usuario = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar la consulta JOIN: " . $this->conn->error);
            return null;
        }
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Actualiza los datos en 'cliente' y 'usuarios' usando una transacción.
     * Ahora actualiza la foto y la contraseña de forma opcional.
     */
    public function update(array $data): bool {
        $this->conn->begin_transaction();

        try {
            // --- 1. Actualización de la tabla 'cliente' ---
            $fieldsCliente = [
                'nombre' => 's', 'telefono' => 's', 'fecha_nacimiento' => 's', 'genero' => 's'
            ];
            if (isset($data['foto_perfil'])) {
                $fieldsCliente['foto_perfil'] = 's';
            }

            // ... (código para construir y ejecutar la consulta de cliente, sin cambios)
            $queryPartsCliente = [];
            $paramsCliente = [];
            $typesCliente = '';
            foreach ($fieldsCliente as $field => $type) {
                if (isset($data[$field])) {
                    $queryPartsCliente[] = "$field = ?";
                    $typesCliente .= $type;
                    $paramsCliente[] = $data[$field];
                }
            }
            if (!empty($queryPartsCliente)) {
                $queryCliente = "UPDATE cliente SET " . implode(', ', $queryPartsCliente) . " WHERE id_usuario = ?";
                $typesCliente .= 'i';
                $paramsCliente[] = $data['id_usuario'];
                $stmtCliente = $this->conn->prepare($queryCliente);
                if (!$stmtCliente) throw new Exception("Error al preparar update de cliente: " . $this->conn->error);
                $stmtCliente->bind_param($typesCliente, ...$paramsCliente);
                $stmtCliente->execute();
            }

            // --- 2. Actualización de la tabla 'usuarios' ---
            $fieldsUsuario = ['correo' => 's'];
            if (isset($data['password'])) {
                $fieldsUsuario['password'] = 's';
            }
            
            $queryPartsUsuario = [];
            $paramsUsuario = [];
            $typesUsuario = '';
            foreach ($fieldsUsuario as $field => $type) {
                if (isset($data[$field])) {
                    $queryPartsUsuario[] = "$field = ?";
                    $typesUsuario .= $type;
                    $paramsUsuario[] = $data[$field];
                }
            }

            if (!empty($queryPartsUsuario)) {
                $queryUsuario = "UPDATE usuarios SET " . implode(', ', $queryPartsUsuario) . " WHERE id_usuario = ?";
                $typesUsuario .= 'i';
                $paramsUsuario[] = $data['id_usuario'];
                $stmtUsuario = $this->conn->prepare($queryUsuario);
                if (!$stmtUsuario) throw new Exception("Error al preparar update de usuarios: " . $this->conn->error);
                
                $stmtUsuario->bind_param($typesUsuario, ...$paramsUsuario);
                $stmtUsuario->execute();
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error en la transacción de actualización: " . $e->getMessage());
            return false;
        }
    }
}