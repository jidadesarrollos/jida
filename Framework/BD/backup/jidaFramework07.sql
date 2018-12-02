-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-12-2018 a las 21:39:14
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `jida`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_clasificaciones`
--

CREATE TABLE `s_clasificaciones` (
  `id_clasificacion` int(11) NOT NULL,
  `clasificacion` varchar(100) DEFAULT NULL,
  `identificador` varchar(100) DEFAULT NULL,
  `padre` int(11) DEFAULT NULL,
  `hijo` int(11) DEFAULT NULL,
  `nombre_clave` varchar(100) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `permiso` int(11) DEFAULT NULL,
  `total_post` int(11) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `id_estatus` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_clasificacion_posts`
--

CREATE TABLE `s_clasificacion_posts` (
  `id_clasificacion_post` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_clasificacion` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_comentarios_posts`
--

CREATE TABLE `s_comentarios_posts` (
  `id_comentario_post` int(11) NOT NULL,
  `comentario_post` text,
  `nombres` varchar(25) DEFAULT NULL,
  `apellidos` varchar(35) DEFAULT NULL,
  `correo` varchar(35) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_Creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_componentes`
--

CREATE TABLE `s_componentes` (
  `id_componente` int(11) NOT NULL,
  `componente` varchar(100) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `identificador` varchar(100) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modifcador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_componentes`
--

INSERT INTO `s_componentes` (`id_componente`, `componente`, `descripcion`, `identificador`, `texto_original`, `id_idioma`, `id_usuario_creador`, `id_usuario_modifcador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'principal', NULL, 'principal', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'jadmin', NULL, 'jadmin', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'admin', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_componentes_perfiles`
--

CREATE TABLE `s_componentes_perfiles` (
  `id_componente_perfil` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_componentes_perfiles`
--

INSERT INTO `s_componentes_perfiles` (`id_componente_perfil`, `id_perfil`, `id_componente`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 1, 2, NULL, NULL, NULL, NULL),
(2, 1, 3, NULL, NULL, NULL, NULL),
(3, 2, 3, NULL, NULL, NULL, NULL),
(4, 1, 1, NULL, NULL, NULL, NULL),
(5, 2, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_elementos`
--

CREATE TABLE `s_elementos` (
  `id_elemento` int(11) NOT NULL,
  `elemento` varchar(50) DEFAULT NULL,
  `data` text,
  `area` varchar(80) DEFAULT NULL,
  `identificador` varchar(100) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_estatus`
--

CREATE TABLE `s_estatus` (
  `id_estatus` int(11) NOT NULL,
  `estatus` varchar(40) DEFAULT NULL,
  `identificador` varchar(80) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_estatus`
--

INSERT INTO `s_estatus` (`id_estatus`, `estatus`, `identificador`, `id_idioma`, `texto_original`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'Activo', 'activo', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Inactivo', 'inactivo', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Eliminado', 'eliminado', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Data Incompleta', 'data_incompleta', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_estatus_posts`
--

CREATE TABLE `s_estatus_posts` (
  `id_estatus_post` int(11) NOT NULL,
  `estatus_post` varchar(80) DEFAULT NULL,
  `identificador` varchar(80) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_idiomas`
--

CREATE TABLE `s_idiomas` (
  `id_idioma` varchar(5) NOT NULL,
  `idioma` varchar(20) DEFAULT NULL,
  `por_defecto` tinyint(4) DEFAULT NULL,
  `identificador` varchar(30) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_idiomas`
--

INSERT INTO `s_idiomas` (`id_idioma`, `idioma`, `por_defecto`, `identificador`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
('esp', 'Español', 1, 'español', NULL, NULL, NULL, NULL),
('fra', 'Frances', NULL, 'frances', NULL, NULL, NULL, NULL),
('ing', 'Ingles', NULL, 'ingles', NULL, NULL, NULL, NULL),
('ita', 'Italiano', NULL, 'italiano', NULL, NULL, NULL, NULL),
('por', 'Portugues', NULL, 'portugues', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_menus`
--

CREATE TABLE `s_menus` (
  `id_menu` int(11) NOT NULL,
  `menu` varchar(50) NOT NULL,
  `meta_data` varchar(200) DEFAULT NULL,
  `identificador` varchar(60) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_menus`
--

INSERT INTO `s_menus` (`id_menu`, `menu`, `meta_data`, `identificador`, `texto_original`, `id_idioma`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'Principal', NULL, 'principal', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Administrador', NULL, 'administrador', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'topCliente', NULL, 'topcliente', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_metodos`
--

CREATE TABLE `s_metodos` (
  `id_metodo` int(11) NOT NULL,
  `id_objeto` int(11) DEFAULT NULL,
  `metodo` varchar(150) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `identificador` varchar(160) DEFAULT NULL,
  `loggin` int(11) DEFAULT '0',
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_metodos`
--

INSERT INTO `s_metodos` (`id_metodo`, `id_objeto`, `metodo`, `descripcion`, `identificador`, `loggin`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(38, 22, 'index', NULL, NULL, 0, NULL, NULL, NULL, NULL),
(39, 23, 'index', NULL, NULL, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_metodos_perfiles`
--

CREATE TABLE `s_metodos_perfiles` (
  `id_metodo_perfil` int(11) NOT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_objetos`
--

CREATE TABLE `s_objetos` (
  `id_objeto` int(11) NOT NULL,
  `id_componente` int(11) DEFAULT NULL,
  `objeto` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `identificador` varchar(120) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_objetos`
--

INSERT INTO `s_objetos` (`id_objeto`, `id_componente`, `objeto`, `descripcion`, `identificador`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(22, 2, 'Jadmin', NULL, 'jadmin', NULL, NULL, NULL, NULL),
(23, 3, 'Admin', NULL, 'admin', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_objetos_media`
--

CREATE TABLE `s_objetos_media` (
  `id_objeto_media` int(11) NOT NULL,
  `objeto_media` varchar(100) NOT NULL,
  `directorio` varchar(100) DEFAULT NULL,
  `tipo_media` int(11) DEFAULT NULL COMMENT '1= imagen; 2 = Video',
  `interno` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `leyenda` varchar(150) DEFAULT NULL,
  `alt` varchar(45) DEFAULT NULL,
  `meta_data` varchar(500) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_objetos_perfiles`
--

CREATE TABLE `s_objetos_perfiles` (
  `id_objeto_perfil` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_objeto` int(11) NOT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_opciones_menu`
--

CREATE TABLE `s_opciones_menu` (
  `id_opcion_menu` int(11) NOT NULL,
  `opcion_menu` varchar(100) NOT NULL,
  `url_opcion` varchar(100) DEFAULT NULL,
  `identificador` varchar(120) DEFAULT NULL,
  `padre` int(11) DEFAULT NULL,
  `hijo` tinyint(1) DEFAULT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `id_estatus` int(11) DEFAULT NULL,
  `selector_icono` int(11) DEFAULT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_opciones_menu`
--

INSERT INTO `s_opciones_menu` (`id_opcion_menu`, `opcion_menu`, `url_opcion`, `identificador`, `padre`, `hijo`, `icono`, `orden`, `id_menu`, `id_estatus`, `selector_icono`, `id_metodo`, `texto_original`, `id_idioma`, `fecha_creacion`, `fecha_modificacion`, `id_usuario_creador`, `id_usuario_modificador`) VALUES
(1, 'Formularios', '/jadmin/forms/', NULL, 0, 1, 'fa fa-check', 2, 1, 1, 1, NULL, NULL, NULL, '2014-02-13 13:01:11', '2014-08-08 10:56:35', NULL, NULL),
(2, 'Menus', '/jadmin/menus/', NULL, 0, 0, 'fa fa-bars', 3, 1, 1, 1, NULL, NULL, NULL, '2014-02-13 13:01:11', NULL, NULL, NULL),
(3, 'ACL', NULL, NULL, 0, 1, 'fa fa-dashboard', 1, 1, 1, 1, NULL, NULL, NULL, '2014-02-13 13:01:11', NULL, NULL, NULL),
(9, 'Perfiles', '/jadmin/perfiles/', NULL, 3, 0, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Cerrar Sesión', '/jadmin/users/cierresesion/', NULL, 0, 0, 'fa fa-power-off', 10, 1, 1, 1, NULL, NULL, NULL, NULL, '2014-09-02 22:30:26', NULL, NULL),
(11, 'Usuarios', '/jadmin/users/', NULL, 3, 0, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_opciones_menu_perfiles`
--

CREATE TABLE `s_opciones_menu_perfiles` (
  `id_opcion_menu_perfil` int(11) NOT NULL,
  `id_opcion_menu` int(11) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_opciones_menu_perfiles`
--

INSERT INTO `s_opciones_menu_perfiles` (`id_opcion_menu_perfil`, `id_opcion_menu`, `id_perfil`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL),
(2, 2, 1, NULL, NULL, NULL, NULL),
(3, 3, 1, NULL, NULL, NULL, NULL),
(6, 9, 1, NULL, NULL, NULL, NULL),
(7, 10, 1, NULL, NULL, NULL, NULL),
(8, 11, 1, NULL, NULL, NULL, NULL),
(12, 1, 1, NULL, NULL, NULL, NULL),
(13, 2, 1, NULL, NULL, NULL, NULL),
(14, 10, 1, NULL, NULL, NULL, NULL),
(15, 3, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_perfiles`
--

CREATE TABLE `s_perfiles` (
  `id_perfil` int(11) NOT NULL,
  `perfil` varchar(50) DEFAULT NULL,
  `fecha_creado` datetime DEFAULT NULL,
  `identificador` varchar(60) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_perfiles`
--

INSERT INTO `s_perfiles` (`id_perfil`, `perfil`, `fecha_creado`, `identificador`, `id_idioma`, `texto_original`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'Jida Administrador', '2014-02-13 13:01:11', 'jadmin', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Administrador', '2014-02-13 13:01:11', 'admin', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Usuario Publico', '2014-02-13 13:01:11', 'publico', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_posts`
--

CREATE TABLE `s_posts` (
  `id_post` int(11) NOT NULL,
  `post` varchar(160) DEFAULT NULL,
  `resumen` varchar(600) DEFAULT NULL,
  `contenido` text,
  `meta_descripcion` varchar(200) DEFAULT NULL,
  `identificador` varchar(180) DEFAULT NULL,
  `relevancia` int(11) DEFAULT NULL,
  `id_media_principal` int(11) DEFAULT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT NULL,
  `numero_visitas` int(11) DEFAULT NULL,
  `id_estatus_post` int(11) DEFAULT NULL,
  `visibilidad` int(11) DEFAULT NULL,
  `nombre_post` varchar(100) DEFAULT NULL,
  `tipo` varchar(25) DEFAULT NULL,
  `data` text,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_usuarios`
--

CREATE TABLE `s_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `identificador` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `id_estatus` int(11) NOT NULL,
  `ultima_session` datetime DEFAULT NULL,
  `validacion` varchar(500) DEFAULT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `codigo_recuperacion` varchar(80) DEFAULT NULL,
  `sexo` int(11) DEFAULT NULL,
  `img_perfil` varchar(100) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_usuarios`
--

INSERT INTO `s_usuarios` (`id_usuario`, `usuario`, `clave`, `identificador`, `activo`, `id_estatus`, `ultima_session`, `validacion`, `nombres`, `apellidos`, `correo`, `codigo_recuperacion`, `sexo`, `img_perfil`, `fecha_creacion`, `fecha_modificacion`, `id_usuario_creador`, `id_usuario_modificador`) VALUES
(1, 'jadmin', 'e10adc3949ba59abbe56e057f20f883e', NULL, 1, 1, '2017-02-26 09:22:50', '1', NULL, NULL, NULL, NULL, NULL, NULL, '2014-02-13 13:01:12', NULL, NULL, NULL),
(2, 'jeanpierre', 'e10adc3949ba59abbe56e057f20f883e', NULL, 1, 1, NULL, '1', NULL, NULL, 'jeacontreras2009@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'felix', 'e10adc3949ba59abbe56e057f20f883e', NULL, 1, 1, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'dayan', 'e10adc3949ba59abbe56e057f20f883e', NULL, 1, 1, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_usuarios_perfiles`
--

CREATE TABLE `s_usuarios_perfiles` (
  `id_usuario_perfil` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_usuarios_perfiles`
--

INSERT INTO `s_usuarios_perfiles` (`id_usuario_perfil`, `id_usuario`, `id_perfil`, `id_usuario_creador`, `id_usuario_modificador`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL),
(2, 1, 2, NULL, NULL, NULL, NULL),
(3, 2, 1, NULL, NULL, NULL, NULL),
(4, 2, 2, NULL, NULL, NULL, NULL),
(5, 3, 1, NULL, NULL, NULL, NULL),
(6, 3, 2, NULL, NULL, NULL, NULL),
(7, 4, 1, NULL, NULL, NULL, NULL),
(8, 4, 2, NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `s_clasificaciones`
--
ALTER TABLE `s_clasificaciones`
  ADD PRIMARY KEY (`id_clasificacion`),
  ADD KEY `fk_s_estatus_idx` (`id_estatus`),
  ADD KEY `fk_s_idiomas_s_clasificacion_post_idx` (`id_idioma`),
  ADD KEY `fk_s_clasificacion_post_texto_original_idx` (`texto_original`);

--
-- Indices de la tabla `s_clasificacion_posts`
--
ALTER TABLE `s_clasificacion_posts`
  ADD PRIMARY KEY (`id_clasificacion_post`),
  ADD KEY `fk_t_posts_r_clasificacion_post_idx` (`id_post`),
  ADD KEY `fk_s_clasificacion_post_r_clasificacion_post_idx` (`id_clasificacion`);

--
-- Indices de la tabla `s_comentarios_posts`
--
ALTER TABLE `s_comentarios_posts`
  ADD PRIMARY KEY (`id_comentario_post`),
  ADD KEY `fk_s_usuarios_idx` (`id_usuario`),
  ADD KEY `fk_t_comentarios_t_post_idx` (`id_post`);

--
-- Indices de la tabla `s_componentes`
--
ALTER TABLE `s_componentes`
  ADD PRIMARY KEY (`id_componente`),
  ADD KEY `fk_s_idiomas_s_componentes_idx` (`id_idioma`),
  ADD KEY `fk_texto_original_s_componentes_idx` (`texto_original`);

--
-- Indices de la tabla `s_componentes_perfiles`
--
ALTER TABLE `s_componentes_perfiles`
  ADD PRIMARY KEY (`id_componente_perfil`),
  ADD KEY `id_perfil` (`id_perfil`),
  ADD KEY `id_componente` (`id_componente`);

--
-- Indices de la tabla `s_elementos`
--
ALTER TABLE `s_elementos`
  ADD PRIMARY KEY (`id_elemento`),
  ADD KEY `fk_s_idiomas_s_elementos_idx` (`id_idioma`),
  ADD KEY `fk_s_elementos_texto_original_idx` (`texto_original`);

--
-- Indices de la tabla `s_estatus`
--
ALTER TABLE `s_estatus`
  ADD PRIMARY KEY (`id_estatus`),
  ADD KEY `fk_s_idiomas_s_estatus_idx` (`id_idioma`),
  ADD KEY `fk_s_idiomas_texto_originas_idx` (`texto_original`);

--
-- Indices de la tabla `s_estatus_posts`
--
ALTER TABLE `s_estatus_posts`
  ADD PRIMARY KEY (`id_estatus_post`),
  ADD KEY `fk_s_idiomas_idx` (`id_idioma`),
  ADD KEY `fk_texto_original_idx` (`texto_original`),
  ADD KEY `sk_s_idiomas_s_estatus_post_idx` (`id_idioma`);

--
-- Indices de la tabla `s_idiomas`
--
ALTER TABLE `s_idiomas`
  ADD PRIMARY KEY (`id_idioma`);

--
-- Indices de la tabla `s_menus`
--
ALTER TABLE `s_menus`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `fk_s_idiomas_s_menus_idx` (`id_idioma`),
  ADD KEY `fk_s_menus_texto_original_idx` (`texto_original`);

--
-- Indices de la tabla `s_metodos`
--
ALTER TABLE `s_metodos`
  ADD PRIMARY KEY (`id_metodo`),
  ADD KEY `id_objeto` (`id_objeto`);

--
-- Indices de la tabla `s_metodos_perfiles`
--
ALTER TABLE `s_metodos_perfiles`
  ADD PRIMARY KEY (`id_metodo_perfil`),
  ADD KEY `id_metodo` (`id_metodo`),
  ADD KEY `id_perfil` (`id_perfil`);

--
-- Indices de la tabla `s_objetos`
--
ALTER TABLE `s_objetos`
  ADD PRIMARY KEY (`id_objeto`),
  ADD KEY `id_componente` (`id_componente`);

--
-- Indices de la tabla `s_objetos_media`
--
ALTER TABLE `s_objetos_media`
  ADD PRIMARY KEY (`id_objeto_media`),
  ADD KEY `fk_s_idiomas_s_objetos_media_idx` (`id_idioma`),
  ADD KEY `fk_s_objetos_media_texto_original_idx` (`texto_original`);

--
-- Indices de la tabla `s_objetos_perfiles`
--
ALTER TABLE `s_objetos_perfiles`
  ADD PRIMARY KEY (`id_objeto_perfil`),
  ADD KEY `id_perfil` (`id_perfil`),
  ADD KEY `id_objeto` (`id_objeto`);

--
-- Indices de la tabla `s_opciones_menu`
--
ALTER TABLE `s_opciones_menu`
  ADD PRIMARY KEY (`id_opcion_menu`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `fk_s_idiomas_s_opciones_menu_idx` (`id_idioma`),
  ADD KEY `fk_s_opciones_menu_texto_original_idx` (`texto_original`),
  ADD KEY `fk_s_estatus_s_opciones_menu_idx` (`id_estatus`),
  ADD KEY `fk_s_metodos_s_opciones_menu_idx` (`id_metodo`);

--
-- Indices de la tabla `s_opciones_menu_perfiles`
--
ALTER TABLE `s_opciones_menu_perfiles`
  ADD PRIMARY KEY (`id_opcion_menu_perfil`),
  ADD KEY `id_opcion` (`id_opcion_menu`),
  ADD KEY `id_perfil` (`id_perfil`);

--
-- Indices de la tabla `s_perfiles`
--
ALTER TABLE `s_perfiles`
  ADD PRIMARY KEY (`id_perfil`),
  ADD KEY `fk_s_idiomas_s_perfiles_idx` (`id_idioma`),
  ADD KEY `fk_s_perfiles_texto_original_idx` (`texto_original`);

--
-- Indices de la tabla `s_posts`
--
ALTER TABLE `s_posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_seccion_idx` (`id_seccion`),
  ADD KEY `id_estatus_post_idx` (`id_estatus_post`),
  ADD KEY `id_idioma_idx` (`id_idioma`),
  ADD KEY `fk_texto_original_idx` (`texto_original`),
  ADD KEY `s_post_s_objetos_media_idx` (`id_media_principal`);

--
-- Indices de la tabla `s_usuarios`
--
ALTER TABLE `s_usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_s_usuarios_s_estatus_idx` (`id_estatus`);

--
-- Indices de la tabla `s_usuarios_perfiles`
--
ALTER TABLE `s_usuarios_perfiles`
  ADD PRIMARY KEY (`id_usuario_perfil`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_perfil` (`id_perfil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `s_clasificaciones`
--
ALTER TABLE `s_clasificaciones`
  MODIFY `id_clasificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `s_comentarios_posts`
--
ALTER TABLE `s_comentarios_posts`
  MODIFY `id_comentario_post` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `s_componentes`
--
ALTER TABLE `s_componentes`
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `s_componentes_perfiles`
--
ALTER TABLE `s_componentes_perfiles`
  MODIFY `id_componente_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `s_elementos`
--
ALTER TABLE `s_elementos`
  MODIFY `id_elemento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `s_estatus`
--
ALTER TABLE `s_estatus`
  MODIFY `id_estatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `s_estatus_posts`
--
ALTER TABLE `s_estatus_posts`
  MODIFY `id_estatus_post` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `s_menus`
--
ALTER TABLE `s_menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `s_metodos`
--
ALTER TABLE `s_metodos`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `s_metodos_perfiles`
--
ALTER TABLE `s_metodos_perfiles`
  MODIFY `id_metodo_perfil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `s_objetos`
--
ALTER TABLE `s_objetos`
  MODIFY `id_objeto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `s_objetos_media`
--
ALTER TABLE `s_objetos_media`
  MODIFY `id_objeto_media` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `s_objetos_perfiles`
--
ALTER TABLE `s_objetos_perfiles`
  MODIFY `id_objeto_perfil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `s_opciones_menu`
--
ALTER TABLE `s_opciones_menu`
  MODIFY `id_opcion_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `s_opciones_menu_perfiles`
--
ALTER TABLE `s_opciones_menu_perfiles`
  MODIFY `id_opcion_menu_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `s_perfiles`
--
ALTER TABLE `s_perfiles`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `s_posts`
--
ALTER TABLE `s_posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `s_usuarios`
--
ALTER TABLE `s_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `s_usuarios_perfiles`
--
ALTER TABLE `s_usuarios_perfiles`
  MODIFY `id_usuario_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `s_clasificaciones`
--
ALTER TABLE `s_clasificaciones`
  ADD CONSTRAINT `fk_s_estatus` FOREIGN KEY (`id_estatus`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_idiomas_s_clasificaciones` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `texto_original_s_clasificaciones` FOREIGN KEY (`texto_original`) REFERENCES `s_clasificaciones` (`id_clasificacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_clasificacion_posts`
--
ALTER TABLE `s_clasificacion_posts`
  ADD CONSTRAINT `fk_s_clasificaciones_s_clasificacion_post` FOREIGN KEY (`id_clasificacion`) REFERENCES `s_clasificaciones` (`id_clasificacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_posts_r_clasificacion_post` FOREIGN KEY (`id_post`) REFERENCES `s_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_comentarios_posts`
--
ALTER TABLE `s_comentarios_posts`
  ADD CONSTRAINT `fk_s_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `s_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_comentarios_t_post` FOREIGN KEY (`id_post`) REFERENCES `s_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_componentes`
--
ALTER TABLE `s_componentes`
  ADD CONSTRAINT `fk_texto_original_s_componentes` FOREIGN KEY (`texto_original`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fks_idiomas_s_componentes` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_componentes_perfiles`
--
ALTER TABLE `s_componentes_perfiles`
  ADD CONSTRAINT `s_componentes_perfiles_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_componentes_perfiles_ibfk_2` FOREIGN KEY (`id_componente`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_elementos`
--
ALTER TABLE `s_elementos`
  ADD CONSTRAINT `fk_s_elementos_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_elementos` (`id_elemento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_idiomas_s_elementos` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_estatus`
--
ALTER TABLE `s_estatus`
  ADD CONSTRAINT `fk_s_estatus_texto_originas` FOREIGN KEY (`texto_original`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_idiomas_s_estatus` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_estatus_posts`
--
ALTER TABLE `s_estatus_posts`
  ADD CONSTRAINT `fk_s_estatus_posts_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_estatus_posts` (`id_estatus_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_idiomas_s_estatus_posts` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_menus`
--
ALTER TABLE `s_menus`
  ADD CONSTRAINT `fk_s_idiomas__s_menus` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_menus_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_metodos`
--
ALTER TABLE `s_metodos`
  ADD CONSTRAINT `s_metodos_ibfk_1` FOREIGN KEY (`id_objeto`) REFERENCES `s_objetos` (`id_objeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_metodos_perfiles`
--
ALTER TABLE `s_metodos_perfiles`
  ADD CONSTRAINT `fk_s_metodos` FOREIGN KEY (`id_metodo`) REFERENCES `s_metodos` (`id_metodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_perfiles` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_objetos`
--
ALTER TABLE `s_objetos`
  ADD CONSTRAINT `fk_s_objetos_s_componentes` FOREIGN KEY (`id_componente`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_objetos_media`
--
ALTER TABLE `s_objetos_media`
  ADD CONSTRAINT `fk_s_idiomas_s_objetos_media` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_objetos_media_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_objetos_media` (`id_objeto_media`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_objetos_perfiles`
--
ALTER TABLE `s_objetos_perfiles`
  ADD CONSTRAINT `s_objetos_perfiles_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_objetos_perfiles_ibfk_2` FOREIGN KEY (`id_objeto`) REFERENCES `s_objetos` (`id_objeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_opciones_menu`
--
ALTER TABLE `s_opciones_menu`
  ADD CONSTRAINT `fk_s_estatus_s_opciones_menu` FOREIGN KEY (`id_estatus`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_idiomas_s_opciones_menu` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_metodos_s_opciones_menu` FOREIGN KEY (`id_metodo`) REFERENCES `s_metodos` (`id_metodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_opciones_menu_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_opciones_menu` (`id_opcion_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_opciones_menu_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_opciones_menu_perfiles`
--
ALTER TABLE `s_opciones_menu_perfiles`
  ADD CONSTRAINT `s_opciones_menu_perfiles_ibfk_1` FOREIGN KEY (`id_opcion_menu`) REFERENCES `s_opciones_menu` (`id_opcion_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_opciones_menu_perfiles_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_perfiles`
--
ALTER TABLE `s_perfiles`
  ADD CONSTRAINT `fk_s_idiomas_s_perfiles` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_s_perfiles_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_posts`
--
ALTER TABLE `s_posts`
  ADD CONSTRAINT `fk_s_idiomas_s_posts` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_estatus_post` FOREIGN KEY (`id_estatus_post`) REFERENCES `s_estatus_posts` (`id_estatus_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_seccion` FOREIGN KEY (`id_seccion`) REFERENCES `s_clasificaciones` (`id_clasificacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_post_s_objetos_media` FOREIGN KEY (`id_media_principal`) REFERENCES `s_objetos_media` (`id_objeto_media`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_usuarios`
--
ALTER TABLE `s_usuarios`
  ADD CONSTRAINT `fk_s_usuarios_s_estatus` FOREIGN KEY (`id_estatus`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_usuarios_perfiles`
--
ALTER TABLE `s_usuarios_perfiles`
  ADD CONSTRAINT `s_usuarios_perfiles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `s_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_usuarios_perfiles_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
