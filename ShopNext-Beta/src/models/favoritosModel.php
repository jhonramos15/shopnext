<?php
// En src/models/FavoritosModel.php

namespace App\Models;

use Config\Database;

class FavoritosModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Verifica si un producto ya es favorito de un usuario.
     */
    private function yaEsFavorito(int $id_usuario, int $id_producto): bool {
        // Obtenemos el id_cliente asociado al id_usuario
        $stmt_cliente = $this->conn->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
        $stmt_cliente->bind_param("i", $id_usuario);
        $stmt_cliente->execute();
        $resultado_cliente = $stmt_cliente->get_result();
        
        if ($resultado_cliente->num_rows === 0) {
            return false; // Si no hay cliente, no puede tener favoritos
        }
        $id_cliente = $resultado_cliente->fetch_assoc()['id_cliente'];
        $stmt_cliente->close();

        // Ahora verificamos en la tabla lista_favoritos con el id_cliente
        $sql = "SELECT id_favorito FROM lista_favoritos WHERE id_cliente = ? AND id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_cliente, $id_producto);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Añade o elimina un producto de los favoritos de un usuario.
     */
    public function toggle(int $id_usuario, int $id_producto): string {
        if ($this->yaEsFavorito($id_usuario, $id_producto)) {
            // Si ya es favorito, lo eliminamos
            $sql = "DELETE FROM lista_favoritos WHERE id_cliente = (SELECT id_cliente FROM cliente WHERE id_usuario = ?) AND id_producto = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_usuario, $id_producto);
            $stmt->execute();
            $stmt->close();
            return 'removed';
        } else {
            // Si no es favorito, lo añadimos
            $stmt_cliente = $this->conn->prepare("SELECT id_cliente FROM cliente WHERE id_usuario = ?");
            $stmt_cliente->bind_param("i", $id_usuario);
            $stmt_cliente->execute();
            $id_cliente = $stmt_cliente->get_result()->fetch_assoc()['id_cliente'];
            $stmt_cliente->close();

            $sql = "INSERT INTO lista_favoritos (id_cliente, id_producto) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_cliente, $id_producto);
            $stmt->execute();
            $stmt->close();
            return 'added';
        }
    }
}