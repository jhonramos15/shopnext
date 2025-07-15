-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-07-2025 a las 23:12:13
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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_BuscarUsuarioPorId` (IN `idUsuario` INT)   BEGIN
    SELECT * FROM usuario WHERE id_usuario;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id_carrito`, `id_cliente`, `fecha_creacion`) VALUES
(1, 48, '2025-07-15 10:25:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `genero` enum('Masculino','Femenino','Otro') DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT 'default_avatar.png',
  `direccion` varchar(200) DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `telefono`, `genero`, `fecha_nacimiento`, `foto_perfil`, `direccion`, `fecha_registro`, `id_usuario`) VALUES
(8, 'Jsjsjs', NULL, NULL, NULL, 'default_avatar.png', '', '2025-05-30', 8),
(24, 'rmkekjwi', NULL, NULL, NULL, 'default_avatar.png', '', '2025-06-24', 24),
(25, 'dfdfffff', NULL, NULL, NULL, 'default_avatar.png', '', '2025-06-24', 25),
(26, 'fddgffgggf', NULL, NULL, NULL, 'default_avatar.png', '', '2025-06-24', 26),
(27, 'ffgghggh', NULL, NULL, NULL, 'default_avatar.png', '', '2025-06-24', 27),
(28, 'dffff', NULL, NULL, NULL, 'default_avatar.png', '', '2025-06-24', 28),
(29, 'dfbgnhjkl', NULL, NULL, NULL, 'default_avatar.png', '', '2025-06-24', 29),
(30, 'aSDFGHJKLÑ', NULL, NULL, NULL, 'default_avatar.png', '', '2025-06-24', 30),
(31, 'sdfvgbmnj.l', NULL, NULL, NULL, 'default_avatar.png', '', '2025-06-25', 31),
(35, 'Otro usuario', NULL, NULL, NULL, 'default_avatar.png', '', '2025-07-05', 37),
(36, 'Ootro usuario xd', NULL, NULL, NULL, 'default_avatar.png', '', '2025-07-05', 38),
(37, 'dsfghjk', NULL, NULL, NULL, 'default_avatar.png', '', '2025-07-05', 39),
(38, 'Usuario 3', NULL, NULL, NULL, 'default_avatar.png', '', '2025-07-05', 41),
(39, 'Usuario 4', NULL, NULL, NULL, 'default_avatar.png', NULL, '0000-00-00', 42),
(47, 'BrayanBG', NULL, NULL, NULL, 'default_avatar.png', NULL, '0000-00-00', 51),
(48, 'BransCliente', '3238818593', NULL, '2006-05-15', 'avatar_6874421258845.png', NULL, '0000-00-00', 54),
(49, '29209298282', NULL, NULL, NULL, 'default_avatar.png', 'callleu28181', '0000-00-00', 57),
(51, 'BransCliente2', '2345678765', 'Masculino', '2000-02-12', 'default_avatar.png', 'Calle 44 # 23 - 23', '0000-00-00', 59);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_detalle`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 4, 13, 20, 100000.00),
(2, 4, 12, 1, 200000.00),
(3, 4, 11, 1, 10000.00),
(4, 4, 15, 3, 23456789.00),
(5, 5, 15, 1, 23456789.00),
(6, 6, 15, 1, 23456789.00),
(7, 7, 15, 1, 23456789.00),
(8, 8, 13, 2, 100000.00),
(9, 8, 16, 2, 2000000.00),
(10, 8, 15, 2, 23456789.00),
(11, 8, 14, 1, 10000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envio`
--

CREATE TABLE `envio` (
  `id_envio` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `estado_envio` enum('pendiente','en camino','entregado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `tipo_pago` varchar(50) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` enum('pendiente','procesado','enviado','entregado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_cliente`, `id_vendedor`, `fecha`, `estado`) VALUES
(4, 48, 6, '2025-07-14', 'pendiente'),
(5, 48, 6, '2025-07-14', 'pendiente'),
(6, 48, 6, '2025-07-15', 'pendiente'),
(7, 48, 6, '2025-07-15', 'pendiente'),
(8, 48, 6, '2025-07-15', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado_pago` varchar(50) NOT NULL DEFAULT 'Pagado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `categoria` varchar(50) DEFAULT NULL,
  `id_vendedor` int(11) NOT NULL,
  `ruta_imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre_producto`, `descripcion`, `precio`, `stock`, `categoria`, `id_vendedor`, `ruta_imagen`) VALUES
(11, 'Comida Animal', 'Comida', 10000.00, 200, 'Otra', 6, 'prod_686de925a2795.png'),
(12, 'Zapatos', 'OK', 200000.00, 200, 'Deporte', 6, 'prod_686f0e31509ed.png'),
(13, 'Monitor', 'Monitor', 100000.00, 18, 'Videojuegos', 6, 'prod_686f53accdc0e.png'),
(14, 'Curology', 'Curology', 10000.00, 12, 'Otro', 6, 'prod_687006e76b79f.png'),
(15, 'Bolso Gucci', 'Bolso', 23456789.00, 117, 'Ropa Femenina', 6, 'prod_6870070bcfaed.png'),
(16, 'GTA VI', 'JUegazo', 2000000.00, 0, 'Videojuegos', 6, 'prod_68767b14395a0.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_carrito`
--

CREATE TABLE `producto_carrito` (
  `id_producto_carrito` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `correo_usuario` varchar(100) NOT NULL,
  `contraseña` varchar(100) NOT NULL,
  `fecha_registro` date NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `rol` enum('cliente','vendedor','admin') NOT NULL DEFAULT 'cliente',
  `token` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL,
  `token_verificacion` varchar(255) DEFAULT NULL,
  `verificado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `correo_usuario`, `contraseña`, `fecha_registro`, `estado`, `rol`, `token`, `token_expira`, `token_recuperacion`, `token_expiracion`, `token_verificacion`, `verificado`) VALUES
(1, 'augs@gmail.com', '$2y$10$FfYHOV0fSEs2FXbcoAhQSumXFoqTD90TaJeReggqAEyikm/apCuhe', '2025-05-22', 'activo', 'vendedor', NULL, NULL, NULL, NULL, NULL, 0),
(3, 'jiju@gmail.com', '$2y$10$dRNAT57I1tCK3BlcqkAXiu0cFXS1NFju6KpWpasrzpjKFtbYIMCkG', '2025-05-22', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(4, 'jijuju@gmail.com', '$2y$10$O8mjDpIVuw2xCj8oMC1JfuUFerJiVbm0uQXVfi4Zzc93SOnVqxByy', '2025-05-22', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(5, 'WEU@gmail.com', '$2y$10$YKI3uzshwceXAHmeQYmZDefBD2ce5AciQYK6ymqx5StrpPGpFUck2', '2025-05-22', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(8, 'jsjs@gmail.com', '$2y$10$DpsC1EGOti.mkPp4TGfMmeECvZolDSK7zBqDhLtuEd7v6s89utepe', '2025-05-30', 'activo', 'vendedor', NULL, NULL, NULL, NULL, NULL, 0),
(10, 'sdsdsds@gmail.cpom', '$2y$10$.Gs/ATh10NKGetWRitPX9Ozy9f6UcN5a4ZkIf85Sx.fW/DTuAWdoW', '2025-05-30', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(11, '9yu@gmail.com', '$2y$10$JFIUAZqvLKc6mqt/yY1lduOCkW.2Q19JNuaj7RC8jwx9bfJbTkszC', '2025-05-30', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(14, 'wewserokpe@gmail.com', '$2y$10$ZbONnyfE9BSLdzcMXSouROrrCyTyS.jlP7rVRACracLg8iOQ8KcJe', '2025-06-17', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(17, 'r8jujier@gmail.com', '$2y$10$ZOc.Ysu5HJ09l9qPdGuow.2UCDw.7iMx6Ucyt2qhvSwZ/aPFDvpeu', '2025-06-18', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(18, 'jakwekirk@gmail.com', '$2y$10$bd9lS6QnrfQ3ri5K4RuSfeifVHZ/d1b8KXOx4RzK2SeLozvU0pPhG', '2025-06-20', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(22, 'ewoij0e@gmail.com', '$2y$10$46PSPA7bBf/.QzkaaTq0x.XNPyQuGv.bPVk9wmDemi3j9ieoowT0C', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(23, '', '$2y$10$6Wef7ZdGPLUuciePBWG47uZhdIb2gyAbgxwUsi3TxVM8LvBWZ./sW', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(24, 'djidiudfi0@gmail.com', '$2y$10$KFXIPxr9d4K.txGFrQfVpeXmM1uXWWUBQL10b4fferKxuM7QWkPZi', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(25, 'dfvffd@gmail.com', '$2y$10$pU.quEOse9qk7hchg8cAfu4D7Si9Rvh9S9tlZ6/xIr17hbAiR7E8C', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(26, 'dssdsdlsd@gmail.com', '$2y$10$LGc6DuYHHWsYgcWqYJroNOimVE6Jib8B2ebKxVMCXq9wuTtai/jMe', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(27, 'enjehj@gmail.com', '$2y$10$i6euET1ShcAwnMWv1ttsHOG3GjNwcMm9uOrf2VwveeXiEH3P.mCPm', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(28, 'dfjlssdjk@gmail.com', '$2y$10$BBt4uKjtR2VCZTf9wUwO6eK3t6jZOV1b.YM8laHge1sZf/sfcIfBu', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(29, 'defghjkl@gmail.com', '$2y$10$oxfkW8QtYSpUqdIjjS3Sze0/UHWi2IqD6h2R8bLhdolExhkPTW1km', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(30, 'asdfghjk@gmail.com', '$2y$10$4rd9LHMNN.af.rM/ulIAW.Se2.7l/FhhkOybpRkCLxAKgQ8dzhTMO', '2025-06-24', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(31, 'werftghujkl@gmail.com', '$2y$10$zND2.0Anvf54GDdQ7c79OutYx1fpxKtw97bFfpRjF00RN4YxH.XMG', '2025-06-25', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(32, 'jak@gmail.com', '$2y$10$437T4FAjZPSiVnoLRIEOAeIlDlEQqNZnrUiBYhT.c3kq.mVUllI/G', '2025-06-25', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(33, 'defjr@gmail.com', '$2y$10$IsFNK.sxfZ.qUVTHWeMnvuOZPYQnksyFxWVPwDwMax2.ZyZcm2Mpq', '2025-06-25', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(34, 'swdefgthuik@gmail.com', '$2y$10$9Y7LfzYUqTKL89RNWAJ1oe3h3zU.uTGn17wJbB.hPVjD8keqFXAnK', '2025-06-25', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 0),
(35, 'vendedor@gmail.com', '$2y$10$DgxOHK3n8g5vqm4J8aCPKeO/m5FqcL0YmWcNk1rXFwuv6Ia/uLELu', '2025-07-04', 'activo', 'vendedor', NULL, NULL, NULL, NULL, NULL, 0),
(36, 'vendedor2@gmail.com', '$2y$10$.uyb2e4APn4OBdMOgQ6xA.spty1WXJE8bODfTmN/JYKSWFwRqff0O', '2025-07-04', 'activo', 'vendedor', NULL, NULL, NULL, NULL, NULL, 0),
(37, 'otrousuario@gmail.com', '$2y$10$7OyV9zWgD2Mt2zmJqidvju2im06c3rB7BYWf3ZhYh1yDBeV68RrOi', '2025-07-05', 'activo', 'cliente', NULL, NULL, NULL, NULL, '90f423d62a61b826e28c3e8a7f2fbf484986e49d1be7a1ea829a9489ccbda99c', 0),
(38, 'ptoeid@gmail.com', '$2y$10$2jj2AX.P/.vOZRlExYZeGOuwHbVOillvz4Va8NzVFqtoJ6fbQnG8u', '2025-07-05', 'activo', 'cliente', NULL, NULL, NULL, NULL, '110c9ed612d1f1deaf3fb117d04d66f964241486dd192fec26a7690f6af2f668', 0),
(39, 'sdfghjkl@gmail.com', '$2y$10$TePaLRlv2d2mur7l9/JWBuPaBOAODWixPuhO7jl13N1oUy2gExJ62', '2025-07-05', 'activo', 'cliente', NULL, NULL, NULL, NULL, '48180a7016da2a7a0e4021f750a907cf504ade272be7a59f062b0cfb98dfe367', 0),
(40, 'vendedor3@gmail.com', '$2y$10$W1EX2wUAHuGXZcmb1vKzFOMVBfLZH1tJa4xFX2Hg2vVp3kHVkvqKy', '2025-07-05', 'activo', 'vendedor', NULL, NULL, NULL, NULL, NULL, 0),
(41, 'usuario3@gmail.com', '$2y$10$9vN1EBaKTUXzLOLhNQZC5ObALb5ezL5jRIDhftU77R/N6BdhHoJKm', '2025-07-05', 'activo', 'cliente', NULL, NULL, NULL, NULL, '40fa97226f963f715e451615bc29e054932bdcb08bd69f4d0d0750b314c9e830', 0),
(42, 'usuario4@gmail.com', '$2y$10$rbZUuA56XA4XC1M9u/7Zuepwp7XllfDPi72yazYocl9EEXMnYMpcW', '2025-07-05', 'activo', 'cliente', NULL, NULL, NULL, NULL, '61a2460c306fdfd4ab8585354f25479c193363d30dc584d272e356ae84f7d0f4', 0),
(51, 'brayan.stiven.ardila.espana@gmail.com', '$2y$10$PmuNV8iuRtih8zBqRq7PKufjMNNl5KTp59u2UGxvMxINA1U.eA/Xu', '2025-07-05', 'activo', 'admin', NULL, NULL, '1397319c669825118e6570c0cd7cd1d5', '2025-07-10 05:32:35', NULL, 1),
(53, 'ardilabrayan42@gmail.com', '$2y$10$SExNC2S0SmDp6Z.Fkm7dm.hDjs4AC.ZrdMg.DHLp6NpWmr0P95N7m', '2025-07-05', 'activo', 'vendedor', NULL, NULL, NULL, NULL, NULL, 1),
(54, 'bransostenso54@gmail.com', '$2y$10$qzKFZb3jhWEYImT/2b.Mzu9Qd2bgTByLYyWWyrJNCNRL6qwoKrM0u', '2025-07-05', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 1),
(55, 'bransstens1@gmail.com', '$2y$10$5Llz3o5yMM/r4mdG1xtXeejHE5qBOAhZyL/rjwCAGucaCfE4dVdq.', '2025-07-10', 'activo', 'vendedor', NULL, NULL, NULL, NULL, '9deecb86e697d816683221dcb15505c63210c138ff23c3847b5289e287f38931', 0),
(56, 'brayanstivenardila111@gmail.com', '$2y$10$392Zt4/p/c7He4H6bUg4Iegz1PfDJL6abJLf0QPWx1bQpEPNtJf8i', '2025-07-10', 'activo', 'vendedor', NULL, NULL, NULL, NULL, NULL, 1),
(57, 'hola1234@hotmail.com', '$2y$10$NCMhKvRFPiuJg592KliBZuc/vPmNnYm0F1/fzXq7N3Zc7ZKKKGtVK', '2025-07-10', 'activo', 'cliente', NULL, NULL, NULL, NULL, NULL, 1),
(59, 'ninjadata2929@gmail.com', '$2y$10$5ZZ2Xn/KQN89D2spkGySeewcQWu/64pUiiOSCZ5y6pf7U8Lnl0OWa', '2025-07-14', 'activo', 'cliente', NULL, NULL, NULL, NULL, '6f632596c00761d9598b91aa62f27eaaed4119ca22281b7fbe48e68e86fa626f', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedor`
--

CREATE TABLE `vendedor` (
  `id_vendedor` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vendedor`
--

INSERT INTO `vendedor` (`id_vendedor`, `nombre`, `direccion`, `correo`, `telefono`, `fecha_registro`, `id_usuario`) VALUES
(1, 'Vendedor', NULL, '', '12345456', '0000-00-00', 35),
(2, 'Vendedor2', NULL, '', '23284576584', '0000-00-00', 36),
(3, 'Vendedor 3', NULL, '', '23456', '0000-00-00', 40),
(6, 'BrayanBG Vendedor', NULL, '', '3238818593', '0000-00-00', 53),
(7, 'uhuh', NULL, '', '4567890', '0000-00-00', 55),
(8, 'WERTYU', NULL, '', '4356789', '0000-00-00', 56);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD UNIQUE KEY `id_cliente_unique` (`id_cliente`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `envio`
--
ALTER TABLE `envio`
  ADD PRIMARY KEY (`id_envio`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- Indices de la tabla `producto_carrito`
--
ALTER TABLE `producto_carrito`
  ADD PRIMARY KEY (`id_producto_carrito`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_usuario` (`correo_usuario`);

--
-- Indices de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id_vendedor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `envio`
--
ALTER TABLE `envio`
  MODIFY `id_envio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `producto_carrito`
--
ALTER TABLE `producto_carrito`
  MODIFY `id_producto_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `id_vendedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `envio`
--
ALTER TABLE `envio`
  ADD CONSTRAINT `envio_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id_vendedor`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `vendedor` (`id_vendedor`);

--
-- Filtros para la tabla `producto_carrito`
--
ALTER TABLE `producto_carrito`
  ADD CONSTRAINT `producto_carrito_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `vendedor`
--
ALTER TABLE `vendedor`
  ADD CONSTRAINT `vendedor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
