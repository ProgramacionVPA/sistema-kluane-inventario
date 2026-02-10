-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-02-2026 a las 01:40:52
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
-- Base de datos: `kluane_inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activos`
--

CREATE TABLE `activos` (
  `id_activo` int(11) NOT NULL,
  `codigo_interno` varchar(50) NOT NULL,
  `serie` varchar(100) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `estado` enum('Operativo','Dañado','Mantenimiento','Baja') DEFAULT 'Operativo',
  `id_categoria` int(11) NOT NULL,
  `id_sede_actual` int(11) NOT NULL,
  `id_usuario_responsable` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `necesita_insumos` enum('SI','NO') DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `activos`
--

INSERT INTO `activos` (`id_activo`, `codigo_interno`, `serie`, `marca`, `modelo`, `estado`, `id_categoria`, `id_sede_actual`, `id_usuario_responsable`, `observaciones`, `fecha_registro`, `necesita_insumos`) VALUES
(2, 'KLU-MON-003', 'MXL123453', 'HP3', 'E243 Monitoreditado', 'Operativo', 2, 1, 4, NULL, '2026-02-05 16:21:47', 'NO'),
(4, 'IT-KDE-LP-002', '(S/N):4NXN8R3', 'DELL', 'Inspiron 15 3000', 'Operativo', 1, 1, 1, NULL, '2026-02-05 17:26:07', 'NO'),
(5, 'IT-KDE-LP-007', '(S/N):2CL36W3', 'ASUS', 'Vivobook', 'Operativo', 1, 1, 1, NULL, '2026-02-05 17:39:45', 'NO'),
(6, 'IT-KDE-LP-024', '(S/N): LANRCV00542341D', 'ASUS', 'Vivobook', 'Operativo', 1, 1, NULL, NULL, '2026-02-06 13:01:24', 'NO'),
(8, 'IT-KDE-LP-005', '(S/N):4NXN8R3', 'HP', 'Notebook', 'Operativo', 1, 1, NULL, NULL, '2026-02-06 18:31:50', 'NO'),
(9, 'IT-KDE-LP-006', '(S/N):4NXN8R3', 'ASUS', 'Vivobook', 'Operativo', 1, 1, 2, NULL, '2026-02-08 21:26:01', 'NO'),
(10, 'IT-KDE-LP-009', 'MXL123453', 'ASUS', 'Inspiron 15 3000', 'Operativo', 1, 2, 4, NULL, '2026-02-09 02:42:49', 'NO'),
(22, 'IT-KDE-LP-031', '(S/N):4NXN8R3', 'ASUS', 'Vivobook', 'Operativo', 1, 2, 3, NULL, '2026-02-09 03:13:05', 'NO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`) VALUES
(1, 'Laptops'),
(2, 'Periféricos'),
(3, 'Herramientas IT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_movimientos`
--

CREATE TABLE `historial_movimientos` (
  `id_historial` int(11) NOT NULL,
  `id_activo` int(11) NOT NULL,
  `id_usuario_responsable` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `observacion` text DEFAULT NULL,
  `tipo_movimiento` enum('Asignacion','Devolucion','Mantenimiento') DEFAULT 'Asignacion',
  `fecha_movimiento` datetime DEFAULT current_timestamp(),
  `ubicacion_destino` varchar(100) DEFAULT 'Desconocido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_movimientos`
--

INSERT INTO `historial_movimientos` (`id_historial`, `id_activo`, `id_usuario_responsable`, `fecha_asignacion`, `observacion`, `tipo_movimiento`, `fecha_movimiento`, `ubicacion_destino`) VALUES
(1, 6, 3, '2026-02-06 15:11:14', 'Entrega completo de insumos ', 'Asignacion', '2026-02-08 20:58:46', 'Desconocido'),
(2, 2, 3, '2026-02-06 15:12:08', 'Prueba', 'Asignacion', '2026-02-08 20:58:46', 'Desconocido'),
(3, 2, 3, '2026-02-06 15:15:11', 'completo', 'Asignacion', '2026-02-08 20:58:46', 'Desconocido'),
(4, 6, 2, '2026-02-06 17:09:48', 'Coller etc ect', 'Asignacion', '2026-02-08 20:58:46', 'Desconocido'),
(5, 8, 3, '2026-02-06 18:32:20', 'fdassdsd', 'Asignacion', '2026-02-08 20:58:46', 'Desconocido'),
(6, 9, 2, '2026-02-08 21:26:16', 'prieba', 'Asignacion', '2026-02-08 20:58:46', 'Desconocido'),
(7, 6, 4, '2026-02-09 02:01:08', 'Cambio realizado en sitio. cambio', '', '2026-02-08 21:01:08', '2'),
(8, 2, 4, '2026-02-09 02:02:55', '', 'Asignacion', '2026-02-08 21:02:55', 'Desconocido'),
(9, 6, 4, '2026-02-09 02:16:42', 'Transferencia enviada por Diego Cifuentes. Motivo: Cierre de proyecto', '', '2026-02-08 21:16:42', '1'),
(10, 6, 3, '2026-02-09 02:18:34', '', 'Asignacion', '2026-02-08 21:18:34', 'Desconocido'),
(11, 6, 3, '2026-02-09 02:26:33', '', 'Asignacion', '2026-02-08 21:26:33', '2'),
(12, 6, 4, '2026-02-09 02:34:26', 'Cambio realizado en sitio. mouse', '', '2026-02-08 21:34:26', '2'),
(13, 8, 4, '2026-02-09 02:35:35', '', 'Asignacion', '2026-02-08 21:35:35', '2'),
(14, 6, 4, '2026-02-09 02:36:50', 'Transferencia enviada por Diego Cifuentes. Motivo: Termina proyecto', '', '2026-02-08 21:36:50', '1'),
(15, 10, 4, '2026-02-09 02:43:03', '', 'Asignacion', '2026-02-08 21:43:03', '2'),
(20, 22, 4, '2026-02-09 03:13:41', 'Insumos completos ', 'Asignacion', '2026-02-08 22:13:41', '2'),
(21, 22, 4, '2026-02-09 03:15:50', 'Cambio realizado en sitio. ', '', '2026-02-08 22:15:50', '2'),
(22, 8, 4, '2026-02-09 03:16:23', 'Transferencia enviada por Diego Cifuentes. Motivo: finalizacion de proyecto', '', '2026-02-08 22:16:23', '1'),
(23, 10, 4, '2026-02-09 03:25:13', 'Transferencia enviada por Diego Cifuentes. Motivo: Envió por daño', '', '2026-02-08 22:25:13', '1'),
(24, 10, 4, '2026-02-09 14:34:23', 'Insumos completos', 'Asignacion', '2026-02-09 09:34:23', '2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'Administrador'),
(2, 'Tecnico'),
(3, 'Colaborador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `id_sede` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id_sede`, `nombre`, `direccion`) VALUES
(1, 'Matriz Quito', NULL),
(2, 'Proyecto Macas', NULL),
(3, 'Proyecto Warintza', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT 'General',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_sede` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_completo`, `area`, `email`, `password`, `id_rol`, `id_sede`) VALUES
(1, 'Victor Admin', 'TI / Infraestructura', 'admin@kluane.com', '$2a$12$26xkCQgFe30r/ARkRk9T1.hFMgq9KXkJM3qCE17Eotkp5XbILn.8m', 1, 1),
(2, 'Juan Perez (Tecnico)', 'Mantenimiento', 'juan.perez@kluane.com', '123456', 2, 2),
(3, 'Maria Lopez (RRHH)', 'Talento Humano', 'maria.lopez@kluane.com', '123456', 2, 1),
(4, 'Diego Cifuentes', 'Logística', 'logistica.macas@kluane.com', '$2a$12$26xkCQgFe30r/ARkRk9T1.hFMgq9KXkJM3qCE17Eotkp5XbILn.8m', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `id_usuario_rol` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`id_usuario_rol`, `id_usuario`, `id_rol`, `fecha_asignacion`) VALUES
(1, 1, 1, '2026-02-08 19:57:25'),
(2, 2, 2, '2026-02-08 19:57:25'),
(3, 3, 2, '2026-02-08 19:57:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activos`
--
ALTER TABLE `activos`
  ADD PRIMARY KEY (`id_activo`),
  ADD UNIQUE KEY `codigo_interno` (`codigo_interno`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_sede_actual` (`id_sede_actual`),
  ADD KEY `id_usuario_responsable` (`id_usuario_responsable`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `historial_movimientos`
--
ALTER TABLE `historial_movimientos`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_activo` (`id_activo`),
  ADD KEY `id_usuario_responsable` (`id_usuario_responsable`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id_sede`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_sede` (`id_sede`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD PRIMARY KEY (`id_usuario_rol`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activos`
--
ALTER TABLE `activos`
  MODIFY `id_activo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `historial_movimientos`
--
ALTER TABLE `historial_movimientos`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id_sede` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  MODIFY `id_usuario_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activos`
--
ALTER TABLE `activos`
  ADD CONSTRAINT `activos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `activos_ibfk_2` FOREIGN KEY (`id_sede_actual`) REFERENCES `sedes` (`id_sede`),
  ADD CONSTRAINT `activos_ibfk_3` FOREIGN KEY (`id_usuario_responsable`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `historial_movimientos`
--
ALTER TABLE `historial_movimientos`
  ADD CONSTRAINT `historial_movimientos_ibfk_1` FOREIGN KEY (`id_activo`) REFERENCES `activos` (`id_activo`),
  ADD CONSTRAINT `historial_movimientos_ibfk_2` FOREIGN KEY (`id_usuario_responsable`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_sede`) REFERENCES `sedes` (`id_sede`);

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
