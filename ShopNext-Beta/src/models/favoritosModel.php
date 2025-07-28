<?php
// src/Models/FavoriteModel.php

namespace App\Models;

use mysqli;

class FavoriteModel {
    private $db;

    public function __construct(mysqli $db) {
        $this->db = $db;
    }

    /**
     * Obtiene los IDs de los productos favoritos de un usuario.
     * @param int $userId El ID del usuario.
     * @return array
     */
    public function getFavoritesByUserId(int $userId): array {
        // Primero, obtenemos el id_cliente a partir del id_usuario
        $sql_cliente = "SELECT id_cliente FROM cliente WHERE id_usuario = ?";
        $stmt_cliente = $this->db->prepare($sql_cliente);
        $stmt_cliente->bind_param("i", $userId);
        $stmt_cliente->execute();
        $result_cliente = $stmt_cliente->get_result();

        if ($result_cliente->num_rows === 0) {
            return []; // El usuario no es un cliente, no puede tener favoritos
        }
        $idCliente = $result_cliente->fetch_assoc()['id_cliente'];
        $stmt_cliente->close();

        // Ahora, obtenemos los favoritos
        $sql_favoritos = "SELECT id_producto FROM lista_favoritos WHERE id_cliente = ?";
        $stmt_favoritos = $this->db->prepare($sql_favoritos);
        $stmt_favoritos->bind_param("i", $idCliente);
        $stmt_favoritos->execute();
        $result_favoritos = $stmt_favoritos->get_result();
        
        $favoriteIds = [];
        while ($row = $result_favoritos->fetch_assoc()) {
            $favoriteIds[] = $row['id_producto'];
        }
        $stmt_favoritos->close();

        return $favoriteIds;
    }
}