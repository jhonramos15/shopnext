-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2025 a las 20:41:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `shopnexs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_compras`
--

CREATE TABLE `carrito_compras` (
  `id_carro` int(11) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito_compras`
--

INSERT INTO `carrito_compras` (`id_carro`, `cantidad`, `fecha`, `id_cliente`, `id_producto`) VALUES
(1, 2, '2023-11-20', 1, 10),
(2, 5, '2023-10-15', 2, 15),
(3, 1, '2023-12-01', 3, 20),
(4, 3, '2023-09-10', 4, 25),
(5, 4, '2023-08-05', 5, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `fecha_registro` date NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `contraseña` varchar(35) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_carro` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `fecha_registro`, `direccion`, `correo`, `contraseña`, `id_usuario`, `id_carro`, `id_pedido`) VALUES
(1, 'Juan Pérez', '2023-01-15', 'Calle 80 # 23 - 34', 'juan.perez@gmail.com', 'JHSDHJOSD', 1, 1, 1),
(2, 'Ana López', '2022-11-20', 'Avenida Suba # 38 - 34', 'ana.lopez@gmail.com', 'securepass456', 2, 2, 2),
(3, 'Carlos Gómez', '2021-05-30', 'Calle 32 # 44 - 45', 'carlos.gomez@gmail.com', 'dddfgfgf', 3, 3, 3),
(4, 'Lucía Ramírez', '2020-09-10', 'Av. NQS Cll 33 # 44 - 34', 'lucia.ramirez@example.com', 'lucypass101', 4, 4, 4),
(5, 'Pedro Torres', '2019-12-25', 'Plaza Mayor 5', 'pedro.torres@example.com', 'torreskey001', 5, 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_envio`
--

CREATE TABLE `detalle_envio` (
  `id_orden` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `cliente` varchar(35) NOT NULL,
  `costo_unidad` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `nombre_producto` varchar(40) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_envio`
--

INSERT INTO `detalle_envio` (`id_orden`, `subtotal`, `cliente`, `costo_unidad`, `cantidad`, `nombre_producto`, `id_pedido`, `id_pago`, `id_producto`) VALUES
(1, 450, 'Juan Pérez', 90, 5, 'Laptop', 1, 1, 1),
(2, 300, 'Ana López', 75, 4, 'Tablet', 2, 2, 2),
(3, 200, 'Carlos Gómez', 50, 4, 'Smartphone', 3, 3, 3),
(4, 600, 'Lucía Ramírez', 100, 6, 'Monitor', 4, 4, 4),
(5, 120, 'Pedro Torres', 40, 3, 'Teclado', 5, 5, 5),
(10, 250, 'Lucía Ramírez', 25, 6, 'Producto A', 4, 4, 13),
(11, 300, 'Pedro Torres', 40, 2, 'Producto B', 5, 5, 14),
(12, 150, 'Ana López', 30, 3, 'Producto Y', 2, 2, 11),
(13, 200, 'Carlos Gómez', 50, 4, 'Producto Z', 3, 3, 12),
(14, 250, 'Lucía Ramírez', 25, 6, 'Producto A', 4, 4, 13),
(15, 300, 'Pedro Torres', 40, 2, 'Producto B', 5, 5, 14),
(16, 150, 'Ana López', 30, 3, 'Producto Y', 2, 2, 11),
(17, 200, 'Carlos Gómez', 50, 4, 'Producto Z', 3, 3, 12),
(18, 250, 'Lucía Ramírez', 25, 6, 'Producto A', 4, 4, 13),
(19, 300, 'Pedro Torres', 40, 2, 'Producto B', 5, 5, 14),
(20, 100, 'Juan Pérez', 20, 5, 'Producto X', 1, 1, 11),
(21, 200, 'Ana López', 30, 3, 'Producto Y', 2, 2, 12),
(22, 150, 'Carlos Gómez', 25, 6, 'Producto Z', 3, 3, 13),
(23, 250, 'Lucía Ramírez', 40, 4, 'Producto A', 4, 4, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_envio`
--

CREATE TABLE `informacion_envio` (
  `id_envio` int(11) NOT NULL,
  `costo` int(11) NOT NULL,
  `region` varchar(35) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `informacion_envio`
--

INSERT INTO `informacion_envio` (`id_envio`, `costo`, `region`, `id_pedido`, `id_venta`) VALUES
(1, 500, 'Norte', 1, 10),
(2, 300, 'Sur', 2, 15),
(3, 450, 'Este', 3, 20),
(4, 600, 'Oeste', 4, 25),
(5, 700, 'Centro', 5, 30),
(101, 500, 'Región A', 1, 1),
(102, 400, 'Región B', 2, 2),
(103, 600, 'Región C', 3, 3),
(104, 300, 'Región D', 4, 4),
(105, 700, 'Región E', 5, 5),
(201, 150, 'Región A', 1, 1),
(202, 200, 'Región B', 2, 2),
(203, 300, 'Región C', 3, 3),
(204, 250, 'Región D', 4, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `tipo_pago` varchar(30) NOT NULL,
  `monto` int(10) NOT NULL,
  `fecha_compra` date NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_detalle_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `tipo_pago`, `monto`, `fecha_compra`, `id_producto`, `id_detalle_pedido`) VALUES
(3, 'Tarjeta de Crédito', 1500, '2023-11-20', 1, 1),
(4, 'Tarjeta de Crédito', 1500, '2023-11-20', 1, 1),
(5, 'PayPal', 750, '2023-10-15', 2, 2),
(6, 'Transferencia Bancaria', 2000, '2023-12-01', 3, 3),
(7, 'Efectivo', 1000, '2023-09-10', 4, 4),
(8, 'Tarjeta de Débito', 500, '2023-08-05', 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `nombre_cliente` varchar(35) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_envio` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_orden` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `fecha`, `nombre_cliente`, `estado`, `id_vendedor`, `id_envio`, `id_cliente`, `id_orden`) VALUES
(1, '2024-12-04', 'Juan Perez', 1, 102, 102, 4, 4),
(2, '2024-01-15', 'Carlos Gómez', 2, 105, 4, 2, 11),
(3, '2024-12-04', 'Lucía Ramírez', 3, 105, 204, 4, 23),
(4, '2024-01-15', 'Pedro Torres', 4, 2, 103, 4, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(35) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `precio` int(10) NOT NULL,
  `categoria` int(3) NOT NULL,
  `id_carro` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `descripcion`, `precio`, `categoria`, `id_carro`, `id_vendedor`, `id_pedido`) VALUES
(1, 'Camiseta de Algodón', 'Camiseta cómoda y suave, 100% algodón', 250, 1, 1, 1, 1),
(2, 'Zapatos Deportivos', 'Zapatos ideales para correr, con suela antideslizante', 1200, 2, 2, 2, 2),
(3, 'Mochila de Viaje', 'Mochila resistente para largas caminatas y viajes', 800, 3, 3, 3, 3),
(4, 'Gorra de Baseball', 'Gorra de algodón con logo bordado', 350, 1, 4, 4, 4),
(5, 'Auriculares Bluetooth', 'Auriculares con cancelación de ruido, Bluetooth 5.0', 1500, 2, 5, 5, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `correo_usuario` varchar(35) NOT NULL,
  `contraseña` varchar(30) NOT NULL,
  `fecha_registro` date NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `correo_usuario`, `contraseña`, `fecha_registro`, `estado`, `id_vendedor`, `id_cliente`) VALUES
(9, 'pedro.usuario@email.com', 'contraseña123', '2024-01-10', 1, 101, 201),
(10, 'ana.usuario@email.com', 'contraseña456', '2024-02-15', 1, 102, 202),
(11, 'luis.usuario@email.com', 'contraseña789', '2024-03-20', 1, 103, 203),
(12, 'marta.usuario@email.com', 'contraseña012', '2024-04-25', 1, 104, 204),
(13, 'carlos.usuario@email.com', 'contraseña345', '2024-05-05', 1, 105, 205);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedor`
--

CREATE TABLE `vendedor` (
  `id_vendedor` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `fecha_registro` date NOT NULL,
  `direccion` varchar(35) NOT NULL,
  `correo` varchar(35) NOT NULL,
  `telefono` int(15) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vendedor`
--

INSERT INTO `vendedor` (`id_vendedor`, `nombre`, `fecha_registro`, `direccion`, `correo`, `telefono`, `id_usuario`, `id_pedido`) VALUES
(1, 'Pedro Martínez', '2024-01-15', 'Calle Ficticia 123, Ciudad A', 'pedro@email.com', 123456789, 1, 1),
(2, 'Ana González', '2024-02-10', 'Avenida Principal 456, Ciudad B', 'ana@email.com', 987654321, 2, 2),
(3, 'Luis Rodríguez', '2024-03-05', 'Calle Secundaria 789, Ciudad C', 'luis@email.com', 564738291, 3, 3),
(4, 'Marta Hernández', '2024-04-20', 'Calle Tercera 101, Ciudad D', 'marta@email.com', 459283746, 4, 4),
(5, 'Carlos Pérez', '2024-05-11', 'Avenida Central 202, Ciudad E', 'carlos@email.com', 192837465, 5, 5),
(101, 'Pedro Martínez', '2024-01-15', 'Calle Ficticia 123, Ciudad A', 'pedro@email.com', 123456789, 1, 1),
(102, 'Ana González', '2024-02-10', 'Avenida Principal 456, Ciudad B', 'ana@email.com', 987654321, 2, 2),
(103, 'Luis Rodríguez', '2024-03-05', 'Calle Secundaria 789, Ciudad C', 'luis@email.com', 564738291, 3, 3),
(104, 'Marta Hernández', '2024-04-20', 'Calle Tercera 101, Ciudad D', 'marta@email.com', 459283746, 4, 4),
(105, 'Carlos Pérez', '2024-05-11', 'Avenida Central 202, Ciudad E', 'carlos@email.com', 192837465, 5, 5),
(106, 'Pedro Martínez', '2024-01-15', 'Calle Ficticia 123, Ciudad A', 'pedro@email.com', 123456789, 101, 201),
(107, 'Ana González', '2024-02-10', 'Avenida Principal 456, Ciudad B', 'ana@email.com', 987654321, 102, 202),
(108, 'Luis Rodríguez', '2024-03-05', 'Calle Secundaria 789, Ciudad C', 'luis@email.com', 564738291, 103, 203),
(109, 'Marta Hernández', '2024-04-20', 'Calle Tercera 101, Ciudad D', 'marta@email.com', 459283746, 104, 204),
(110, 'Carlos Pérez', '2024-05-11', 'Avenida Central 202, Ciudad E', 'carlos@email.com', 192837465, 105, 205);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id_venta` int(11) NOT NULL,
  `lista_producto` varchar(30) NOT NULL,
  `id_envio` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id_venta`, `lista_producto`, `id_envio`, `id_vendedor`) VALUES
(17, 'Producto A, Producto B', 101, 1),
(18, 'Producto C, Producto D', 102, 2),
(19, 'Producto E, Producto F', 103, 3),
(20, 'Producto G, Producto H', 104, 4),
(21, 'Producto I, Producto J', 105, 5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD PRIMARY KEY (`id_carro`),
  ADD KEY `carrito_compras_ibfk_1` (`id_producto`),
  ADD KEY `carrito_compras_ibfk_2` (`id_cliente`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_carro` (`id_carro`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `detalle_envio`
--
ALTER TABLE `detalle_envio`
  ADD PRIMARY KEY (`id_orden`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_pago` (`id_pago`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `informacion_envio`
--
ALTER TABLE `informacion_envio`
  ADD PRIMARY KEY (`id_envio`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_detalle_pedido` (`id_detalle_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `id_envio` (`id_envio`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_orden` (`id_orden`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- Indices de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id_vendedor`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  MODIFY `id_carro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_envio`
--
ALTER TABLE `detalle_envio`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `informacion_envio`
--
ALTER TABLE `informacion_envio`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `id_vendedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_2` FOREIGN KEY (`id_detalle_pedido`) REFERENCES `detalle_envio` (`id_orden`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id_vendedor`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_envio`) REFERENCES `informacion_envio` (`id_envio`),
  ADD CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `pedido_ibfk_4` FOREIGN KEY (`id_orden`) REFERENCES `detalle_envio` (`id_orden`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id_vendedor`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id_vendedor`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
