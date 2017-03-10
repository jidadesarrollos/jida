-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-03-2017 a las 23:55:02
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crea_modulo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_menus`
--

CREATE TABLE `s_menus` (
  `id_menu` int(11) NOT NULL,
  `nombre_menu` varchar(30) NOT NULL,
  `meta_data` varchar(200) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_menus`
--

INSERT INTO `s_menus` (`id_menu`, `nombre_menu`, `meta_data`, `fecha_creacion`, `fecha_modificacion`, `id_usuario_creador`, `id_usuario_modificador`) VALUES
(1, 'Principal', NULL, '2017-03-09 00:47:20', '0000-00-00 00:00:00', NULL, NULL),
(2, 'Administrador', NULL, '2017-03-09 00:47:20', '0000-00-00 00:00:00', NULL, NULL),
(3, 'topCliente', NULL, '2017-03-09 00:47:20', '0000-00-00 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_opciones_menu`
--

CREATE TABLE `s_opciones_menu` (
  `id_opcion_menu` int(11) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `url_opcion` varchar(100) DEFAULT NULL,
  `nombre_opcion` varchar(100) NOT NULL,
  `padre` int(11) DEFAULT NULL,
  `hijo` tinyint(1) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `id_estatus` int(11) DEFAULT NULL,
  `selector_icono` int(11) DEFAULT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_opciones_menu`
--

INSERT INTO `s_opciones_menu` (`id_opcion_menu`, `id_menu`, `url_opcion`, `nombre_opcion`, `padre`, `hijo`, `fecha_creacion`, `fecha_modificacion`, `icono`, `orden`, `id_estatus`, `selector_icono`, `id_metodo`, `id_usuario_creador`, `id_usuario_modificador`) VALUES
(1, 1, '/jadmin/forms/', 'Formularios', 0, 1, '2014-02-13 13:01:11', '2014-08-08 10:56:35', NULL, NULL, 1, 1, NULL, NULL, NULL),
(2, 1, '/jadmin/menus/', 'Menus', 0, 0, '2014-02-13 13:01:11', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(3, 1, NULL, 'ACL', 0, 1, '2014-02-13 13:01:11', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(4, 1, '/jadmin/objetos/', 'Objetos', 3, 0, '2014-02-13 13:01:11', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(5, 1, '/jadmin/componentes/', 'Componentes', 3, 0, '2014-02-13 13:01:11', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(9, 1, '/jadmin/perfiles/', 'Perfiles', 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(10, 1, '/jadmin/users/cierresesion/', 'Cerrar Sesi&oacute;n', 0, 0, NULL, '2014-09-02 22:30:26', NULL, NULL, 1, 1, NULL, NULL, NULL),
(11, 1, '/jadmin/users/', 'Usuarios', 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(27, 1, '/jadmin/forms/jida-forms', 'Jida', 1, 0, '2014-08-04 05:31:21', '2014-08-08 10:37:52', NULL, NULL, 1, 1, NULL, NULL, NULL),
(28, 1, '/jadmin/forms/filter/aplicacion', 'Aplicaci&oacute;n', 1, 0, '2014-08-04 05:54:06', '2014-08-04 05:54:06', 'fa-plus-square-o', 2, 1, 1, NULL, NULL, NULL),
(29, 1, '/algo-distinto/', '1', 1, 0, '2014-08-08 10:57:10', '2014-08-08 10:57:10', NULL, 10, 1, 1, NULL, NULL, NULL),
(30, 1, '/jadmin/modulos/', 'Modulos', 0, 0, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL),
(31, 1, 'mimenu', 'mi prueba', 1, 0, '2017-03-09 22:12:44', '2017-03-09 22:12:44', NULL, 11, 1, 1, NULL, 3, 3),
(33, NULL, 'otraOpcion', 'otra prueba', NULL, NULL, '2017-03-10 16:08:18', '2017-03-10 16:08:18', NULL, 33, 1, 1, NULL, 3, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `s_menus`
--
ALTER TABLE `s_menus`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indices de la tabla `s_opciones_menu`
--
ALTER TABLE `s_opciones_menu`
  ADD PRIMARY KEY (`id_opcion_menu`),
  ADD KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `s_menus`
--
ALTER TABLE `s_menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `s_opciones_menu`
--
ALTER TABLE `s_opciones_menu`
  MODIFY `id_opcion_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `s_opciones_menu`
--
ALTER TABLE `s_opciones_menu`
  ADD CONSTRAINT `s_opciones_menu_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
