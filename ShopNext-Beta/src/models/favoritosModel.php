<?php
// src/models/FavoritosModel.php
namespace App\Models;

use Config\Database;

class FavoritosModel {
    private $conn;

    public function __construct() {
        $this->conn = (new \Config\Database())->getConnection();
    }

    /**
     * Obtiene todos los productos favoritos de un usuario.
     * @param int $id_usuario El ID del usuario.
     * @return array La lista de productos favoritos.
     */
    public function getFavoritesByUserId(int $id_usuario): array {
        // Obtenemos el id_cliente asociado al id_usuario
        $stmt_cliente = $this->conn->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
        $stmt_cliente->bind_param("i", $id_usuario);
        $stmt_cliente->execute();
        $result_cliente = $stmt_cliente->get_result();
        
        if ($result_cliente->num_rows === 0) {
            return []; // El usuario no tiene un perfil de cliente
        }
        $id_cliente = $result_cliente->fetch_assoc()['id_cliente'];
        $stmt_cliente->close();

        // Usamos el id_cliente para obtener los productos de la lista de favoritos
        $sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.ruta_imagen
                FROM producto p
                JOIN lista_favoritos lf ON p.id_producto = lf.id_producto
                WHERE lf.id_cliente = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Agrega o quita un producto de la lista de favoritos.
     * @param int $id_usuario El ID del usuario.
     * @param int $id_producto El ID del producto.
     * @return string 'added' si se agregó, 'removed' si se quitó.
     */
    public function toggle(int $id_usuario, int $id_producto): string {
        $id_cliente = $this->getClienteId($id_usuario);
        if (!$id_cliente) {
            throw new \Exception("Cliente no encontrado para el usuario.");
        }

        // Revisar si ya existe
        $stmt_check = $this->conn->prepare("SELECT id_favorito FROM lista_favoritos WHERE id_cliente = ? AND id_producto = ?");
        $stmt_check->bind_param("ii", $id_cliente, $id_producto);
        $stmt_check->execute();
        $exists = $stmt_check->get_result()->fetch_assoc();
        $stmt_check->close();

        if ($exists) {
            // Si existe, lo borramos
            $stmt_delete = $this->conn->prepare("DELETE FROM lista_favoritos WHERE id_favorito = ?");
            $stmt_delete->bind_param("i", $exists['id_favorito']);
            $stmt_delete->execute();
            return 'removed';
        } else {
            // Si no existe, lo insertamos
            $stmt_insert = $this->conn->prepare("INSERT INTO lista_favoritos (id_cliente, id_producto) VALUES (?, ?)");
            $stmt_insert->bind_param("ii", $id_cliente, $id_producto);
            $stmt_insert->execute();
            return 'added';
        }
    }

    /**
     * Helper para obtener el id_cliente a partir del id_usuario
     */
    private function getClienteId(int $id_usuario): ?int {
        $stmt = $this->conn->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['id_cliente'] ?? null;
    }
}