CREATE DATABASE IF NOT EXISTS tienda;
USE tienda;

CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto VARCHAR(100),
    precio DECIMAL(10,2),
    cantidad INT
);

DELIMITER $$

CREATE PROCEDURE sp_agregarProducto(IN p_producto VARCHAR(100), IN p_precio DECIMAL(10,2), IN p_cantidad INT)
BEGIN
    INSERT INTO carrito (producto, precio, cantidad) VALUES (p_producto, p_precio, p_cantidad);
END$$

CREATE PROCEDURE sp_obtenerCarrito()
BEGIN
    SELECT * FROM carrito;
END$$

CREATE PROCEDURE sp_actualizarCantidad(IN p_id INT, IN p_cantidad INT)
BEGIN
    UPDATE carrito SET cantidad = p_cantidad WHERE id = p_id;
END$$

CREATE PROCEDURE sp_eliminarProducto(IN p_id INT)
BEGIN
    DELETE FROM carrito WHERE id = p_id;
END$$

DELIMITER ;
