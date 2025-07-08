-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-07-2025 a las 11:01:10
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizarCantidad` (IN `p_id` INT, IN `p_cantidad` INT)   BEGIN
    UPDATE carrito SET cantidad = p_cantidad WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_agregarDeseo` (IN `p_producto` VARCHAR(100), IN `p_precio` INT, IN `p_imagen` VARCHAR(255))   BEGIN
    -- Evitar duplicados
    IF NOT EXISTS (SELECT 1 FROM wishlist WHERE producto = p_producto) THEN
        INSERT INTO wishlist (producto, precio, imagen)
        VALUES (p_producto, p_precio, p_imagen);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_agregarProducto` (IN `p_producto` VARCHAR(100), IN `p_precio` INT, IN `p_cantidad` INT, IN `p_imagen` VARCHAR(255))   BEGIN
    INSERT INTO carrito (producto, precio, cantidad, imagen) 
    VALUES (p_producto, p_precio, p_cantidad, p_imagen);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contarCarrito` ()   BEGIN
    SELECT COUNT(id) as total FROM carrito;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_contarDeseos` ()   BEGIN
    SELECT COUNT(*) as total FROM wishlist;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminarDeseo` (IN `p_id` INT)   BEGIN
    DELETE FROM wishlist WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_eliminarProducto` (IN `p_id` INT)   BEGIN
    DELETE FROM carrito WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_moverDeseoACarrito` (IN `p_id` INT)   BEGIN
    -- Insertar en carrito
    INSERT INTO carrito (producto, precio, cantidad, imagen)
    SELECT producto, precio, 1, imagen FROM wishlist WHERE id = p_id;
    -- Eliminar de wishlist
    DELETE FROM wishlist WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_moverTodoACarrito` ()   BEGIN
    -- Insertar todos los productos que no estén ya en el carrito
    INSERT INTO carrito (producto, precio, cantidad, imagen)
    SELECT w.producto, w.precio, 1, w.imagen FROM wishlist w
    LEFT JOIN carrito c ON w.producto = c.producto
    WHERE c.id IS NULL;
    -- Limpiar la wishlist
    DELETE FROM wishlist;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtenerCarrito` ()   BEGIN
    SELECT * FROM carrito;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_obtenerDeseos` ()   BEGIN
    SELECT * FROM wishlist;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `producto` varchar(100) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id`, `producto`, `precio`, `cantidad`, `imagen`) VALUES
(60, 'S-Series Comfort Chair', 670000, 1, 'img/silla.png'),
(61, 'Gaming Headset X7', 480000, 1, 'img/diademas.webp'),
(62, 'Wireless Mouse Pro', 220000, 1, 'img/mouse.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `producto` varchar(100) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `wishlist`
--

INSERT INTO `wishlist` (`id`, `producto`, `precio`, `imagen`) VALUES
(23, 'Portatil ASUS A14', 2300000, 'img/asus.png'),
(24, 'AK-900 Wired Keyboard', 650000, 'img/teclado.webp'),
(25, 'IPS LCD Gaming Monitor', 870000, 'img/pantalla.webp'),
(28, 'Wireless Mouse Pro', 220000, 'img/mouse.webp');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
