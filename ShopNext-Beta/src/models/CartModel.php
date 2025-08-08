<?php
// En src/Models/CartModel.php

namespace App\Models;

class CartModel
{
    private $conn;

    // Usamos la inyección de dependencias para recibir la conexión
    public function __construct($db_connection)
    {
        $this->conn = $db_connection;
    }

    /**
     * Obtiene el ID del carrito de un usuario. Si no existe, lo crea.
     * @param int $id_cliente
     * @return int ID del carrito.
     */
    public function getOrCreateCart(int $id_cliente): int
    {
        // 1. Buscar el carrito existente
        $stmt = $this->conn->prepare("SELECT id_carrito FROM carrito WHERE id_cliente = ?");
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc()['id_carrito'];
        }

        // 2. Si no existe, crear uno nuevo
        $stmt = $this->conn->prepare("INSERT INTO carrito (id_cliente) VALUES (?)");
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    /**
     * Añade un producto al carrito o actualiza su cantidad si ya existe.
     * @param int $id_carrito
     * @param int $id_producto
     * @param int $cantidad
     * @return bool
     */
    public function addItem(int $id_carrito, int $id_producto, int $cantidad): bool
    {
        // Verificar si el producto ya está en el carrito
        $stmt = $this->conn->prepare("SELECT id_producto_carrito, cantidad FROM producto_carrito WHERE id_carrito = ? AND id_producto = ?");
        $stmt->bind_param("ii", $id_carrito, $id_producto);
        $stmt->execute();
        $existing_item = $stmt->get_result()->fetch_assoc();

        if ($existing_item) {
            // Si existe, actualiza la cantidad
            $new_quantity = $existing_item['cantidad'] + $cantidad;
            $stmt = $this->conn->prepare("UPDATE producto_carrito SET cantidad = ? WHERE id_producto_carrito = ?");
            $stmt->bind_param("ii", $new_quantity, $existing_item['id_producto_carrito']);
        } else {
            // Si no existe, lo inserta
            $stmt = $this->conn->prepare("INSERT INTO producto_carrito (id_carrito, id_producto, cantidad) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $id_carrito, $id_producto, $cantidad);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Obtiene todos los productos de un carrito específico.
     * @param int $id_carrito
     * @return array
     */
    public function getCartItems(int $id_carrito): array
    {
    $sql = "SELECT p.nombre_producto, p.precio, p.ruta_imagen, pc.cantidad, pc.id_producto, pc.id_producto_carrito
            FROM producto_carrito pc
            JOIN producto p ON pc.id_producto = p.id_producto
            WHERE pc.id_carrito = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_carrito);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Elimina un producto específico del carrito.
     * @param int $id_carrito
     * @param int $id_producto
     * @return bool
     */
    public function removeItem(int $id_carrito, int $id_producto): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM producto_carrito WHERE id_carrito = ? AND id_producto = ?");
        $stmt->bind_param("ii", $id_carrito, $id_producto);
        return $stmt->execute();
    }

    /**
     * Vacía todos los productos de un carrito.
     * @param int $id_carrito
     * @return bool
     */
    public function clearCart(int $id_carrito): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM producto_carrito WHERE id_carrito = ?");
        $stmt->bind_param("i", $id_carrito);
        return $stmt->execute();
    }
}