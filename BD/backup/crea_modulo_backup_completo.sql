-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-03-2017 a las 00:16:12
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
-- Estructura de tabla para la tabla `s_campos_f`
--

CREATE TABLE `s_campos_f` (
  `id_campo` int(11) NOT NULL,
  `id_form` int(11) NOT NULL,
  `label` varchar(80) DEFAULT NULL,
  `name` varchar(60) NOT NULL,
  `maxlength` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `eventos` text,
  `control` int(11) DEFAULT NULL,
  `opciones` text,
  `orden` int(11) DEFAULT NULL,
  `id_propiedad` varchar(50) DEFAULT NULL,
  `placeholder` varchar(50) DEFAULT NULL,
  `class` varchar(100) DEFAULT NULL,
  `data_atributo` varchar(500) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `visibilidad` int(11) DEFAULT '1',
  `ayuda` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_campos_f`
--

INSERT INTO `s_campos_f` (`id_campo`, `id_form`, `label`, `name`, `maxlength`, `size`, `eventos`, `control`, `opciones`, `orden`, `id_propiedad`, `placeholder`, `class`, `data_atributo`, `title`, `visibilidad`, `ayuda`) VALUES
(1, 1, '', 'id_form', NULL, NULL, '', 1, '', 0, 'id_form', '', '', '', NULL, 1, NULL),
(2, 1, 'Nombre Formulario', 'nombre_f', 30, 30, '\"obligatorio\":{\"mensaje\":\"Debes ingresar un nombre que identifique al formulario\"}', 2, NULL, 1, 'nombre_f', NULL, NULL, NULL, NULL, 1, NULL),
(3, 1, 'Query', 'query_f', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}', 3, NULL, 2, 'query_f', NULL, NULL, NULL, NULL, 1, NULL),
(4, 1, 'Clave Primaria', 'clave_primaria_f', 30, 30, '', 2, '', 1, 'clave_primaria_f', '', '', '', NULL, 1, NULL),
(5, 2, 'a', 'id_form', NULL, NULL, NULL, 1, NULL, 2, 'id_form', NULL, NULL, NULL, NULL, 1, NULL),
(6, 2, NULL, 'id_campo', NULL, 3, NULL, 1, NULL, 1, 'id_campo', NULL, NULL, NULL, NULL, 1, NULL),
(7, 2, 'Data', 'data_atributo', 100, 40, '', 3, '', 13, 'data_atributo', '', '', 'data-jidacontrol=\"Ingrese un PlaceHolder\"', NULL, 1, NULL),
(8, 2, 'Clase', 'class', 30, 50, NULL, 2, NULL, 17, 'class', NULL, NULL, NULL, NULL, 1, NULL),
(9, 2, 'ID Propiedad', 'id_propiedad', 30, 30, '\"obligatorio\":{\"Mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}', 2, NULL, 5, 'id_propiedad', NULL, NULL, NULL, NULL, 1, NULL),
(10, 2, 'Orden', 'orden', 20, 20, '', 2, '', 18, 'orden', '', '', '', NULL, 1, NULL),
(11, 2, 'Opciones', 'opciones', NULL, NULL, '', 3, '', 9, 'opciones', '', '', '', '', 1, NULL),
(12, 2, 'Tipo de Control', 'control', NULL, NULL, NULL, 7, '=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion;9=Telefono', 5, 'control', NULL, NULL, NULL, NULL, 1, NULL),
(13, 2, 'Eventos', 'eventos', NULL, NULL, '', 3, '', 10, 'eventos', '', '', '', '', 1, NULL),
(14, 2, 'Size', 'size', 20, 20, '', 2, '', 8, 'size', '', '', '', '', 1, NULL),
(15, 2, 'Maxlength', 'maxlength', 20, 20, '', 2, '', 7, 'maxlength', '', '', '', '', 1, NULL),
(16, 2, 'Name', 'name', 30, 30, '\"obligatorio\":{\"Mensaje\":\"El campo debe llevar un nombre\"},\"programa\":{\"Mensaje\":\"El nombre no es valido\"}', 2, '', 3, 'name', '', '', '', '', 1, NULL),
(17, 2, 'Label', 'label', 30, 100, NULL, 2, NULL, 6, 'label', NULL, NULL, NULL, NULL, 1, NULL),
(18, 2, 'Placeholder', 'placeholder', 100, 30, NULL, 2, NULL, 14, 'placeholder', 'hola mundo', NULL, NULL, NULL, 1, NULL),
(19, 2, 'Title', 'title', NULL, NULL, '', 2, '', 15, 'title', '', '', '', '', 1, NULL),
(20, 2, 'Visibilidad', 'visibilidad', NULL, NULL, NULL, 7, '=Seleccione...;1=Normal;2=Readonly;3=Disabled', 16, 'visibilidad', NULL, NULL, NULL, NULL, 1, NULL),
(42, 1, 'Estructura', 'estructura', 50, 50, NULL, 2, NULL, 4, 'estructura', 'Estructura', NULL, NULL, 'Estructura de creacion del formulario', 1, NULL),
(43, 2, 'Clave', 'clave_evento', 40, 40, NULL, 2, NULL, 11, 'clave_evento', NULL, NULL, NULL, NULL, 1, NULL),
(44, 2, 'Valor Evento', 'valor_evento', 40, 40, NULL, NULL, NULL, 12, 'valor_evento', NULL, NULL, NULL, NULL, 1, NULL),
(49, 14, NULL, 'id_campo', NULL, NULL, NULL, 2, NULL, NULL, 'id_campo', NULL, NULL, NULL, NULL, 1, NULL),
(50, 14, NULL, 'id_form', NULL, NULL, NULL, 2, NULL, NULL, 'id_form', NULL, NULL, NULL, NULL, 1, NULL),
(51, 14, NULL, 'label', NULL, NULL, NULL, 2, NULL, NULL, 'label', NULL, NULL, NULL, NULL, 1, NULL),
(52, 14, NULL, 'name', NULL, NULL, NULL, 2, NULL, NULL, 'name', NULL, NULL, NULL, NULL, 1, NULL),
(53, 14, NULL, 'maxlength', NULL, NULL, NULL, 2, NULL, NULL, 'maxlength', NULL, NULL, NULL, NULL, 1, NULL),
(54, 14, NULL, 'size', NULL, NULL, NULL, 2, NULL, NULL, 'size', NULL, NULL, NULL, NULL, 1, NULL),
(55, 14, NULL, 'eventos', NULL, NULL, NULL, 2, NULL, NULL, 'eventos', NULL, NULL, NULL, NULL, 1, NULL),
(56, 14, NULL, 'control', NULL, NULL, NULL, 2, NULL, NULL, 'control', NULL, NULL, NULL, NULL, 1, NULL),
(57, 14, NULL, 'opciones', NULL, NULL, NULL, 2, NULL, NULL, 'opciones', NULL, NULL, NULL, NULL, 1, NULL),
(58, 14, NULL, 'orden', NULL, NULL, NULL, 2, NULL, NULL, 'orden', NULL, NULL, NULL, NULL, 1, NULL),
(59, 14, NULL, 'id_propiedad', NULL, NULL, NULL, 2, NULL, NULL, 'id_propiedad', NULL, NULL, NULL, NULL, 1, NULL),
(60, 14, NULL, 'placeholder', NULL, NULL, NULL, 2, NULL, NULL, 'placeholder', NULL, NULL, NULL, NULL, 1, NULL),
(61, 14, NULL, 'class', NULL, NULL, NULL, 2, NULL, NULL, 'class', NULL, NULL, NULL, NULL, 1, NULL),
(62, 14, NULL, 'data_atributo', NULL, NULL, NULL, 2, NULL, NULL, 'data_atributo', NULL, NULL, NULL, NULL, 1, NULL),
(63, 14, 'testing nor', 'title', NULL, NULL, NULL, 2, 'a', NULL, 'title', NULL, NULL, NULL, NULL, 1, NULL),
(64, 14, NULL, 'visibilidad', NULL, NULL, NULL, 2, NULL, NULL, 'visibilidad', NULL, NULL, NULL, NULL, 1, NULL),
(65, 14, NULL, 'ayuda', NULL, NULL, NULL, 2, NULL, NULL, 'ayuda', NULL, NULL, NULL, NULL, 1, NULL),
(66, 15, 'Como estas papa', 'algo', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"algo para que se vea\"}', 2, NULL, NULL, 'algo', NULL, NULL, NULL, NULL, 1, NULL),
(67, 15, NULL, 'como', NULL, NULL, NULL, 2, NULL, NULL, 'como', NULL, NULL, NULL, NULL, 1, NULL),
(68, 15, NULL, 'algo_mas', NULL, NULL, NULL, 2, NULL, NULL, 'algo_mas', NULL, NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_componentes`
--

CREATE TABLE `s_componentes` (
  `id_componente` int(11) NOT NULL,
  `componente` varchar(100) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_componentes`
--

INSERT INTO `s_componentes` (`id_componente`, `componente`, `descripcion`) VALUES
(1, 'principal', NULL),
(2, 'jadmin', NULL),
(3, 'admin', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_componentes_perfiles`
--

CREATE TABLE `s_componentes_perfiles` (
  `id_componente_perfil` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_componentes_perfiles`
--

INSERT INTO `s_componentes_perfiles` (`id_componente_perfil`, `id_perfil`, `id_componente`) VALUES
(4, 1, 2),
(11, 1, 3),
(12, 2, 3),
(13, 1, 1),
(14, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_estatus`
--

CREATE TABLE `s_estatus` (
  `id_estatus` int(11) NOT NULL,
  `estatus` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_estatus`
--

INSERT INTO `s_estatus` (`id_estatus`, `estatus`) VALUES
(1, 'Activo'),
(2, 'Inactivo'),
(3, 'Eliminado'),
(4, 'Data Incompleta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_formularios`
--

CREATE TABLE `s_formularios` (
  `id_form` int(11) NOT NULL,
  `nombre_f` varchar(80) NOT NULL,
  `query_f` text NOT NULL,
  `clave_primaria_f` varchar(45) DEFAULT NULL,
  `nombre_identificador` varchar(100) NOT NULL,
  `estructura` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_formularios`
--

INSERT INTO `s_formularios` (`id_form`, `nombre_f`, `query_f`, `clave_primaria_f`, `nombre_identificador`, `estructura`) VALUES
(1, 'formularios', 'select id_form,nombre_f,query_f,clave_primaria_f,estructura from s_formularios', 'id_form', 'Formularios', NULL),
(2, 'Campos Formulario', 'select id_campo, id_form, label, name, maxlength, size,\r\neventos, 1 clave_evento, 2 valor_evento, control,  opciones, orden, id_propiedad, placeholder,\r\nclass, data_atributo, title, visibilidad from s_campos_f', 'id_campo', 'CamposFormulario', '2;3;1;2;1x2;2;1x3;3'),
(14, 'testing', 'select * from s_campos_f', 'id_campo', 'Testing', NULL),
(15, 'Formulario de prueba', 'select 1 as algo, 2 as como, 3 as algo_mas from s_campos_f', NULL, 'FormularioDePrueba', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_jida_campos_f`
--

CREATE TABLE `s_jida_campos_f` (
  `id_campo` int(11) NOT NULL,
  `id_form` int(11) NOT NULL,
  `label` varchar(80) DEFAULT NULL,
  `name` varchar(60) NOT NULL,
  `maxlength` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `eventos` text,
  `control` int(11) DEFAULT NULL,
  `opciones` text,
  `orden` int(11) DEFAULT NULL,
  `id_propiedad` varchar(50) DEFAULT NULL,
  `placeholder` varchar(50) DEFAULT NULL,
  `class` varchar(100) DEFAULT NULL,
  `data_atributo` varchar(500) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `visibilidad` int(11) DEFAULT '1',
  `ayuda` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_jida_campos_f`
--

INSERT INTO `s_jida_campos_f` (`id_campo`, `id_form`, `label`, `name`, `maxlength`, `size`, `eventos`, `control`, `opciones`, `orden`, `id_propiedad`, `placeholder`, `class`, `data_atributo`, `title`, `visibilidad`, `ayuda`) VALUES
(1, 1, '', 'id_form', NULL, NULL, '', 1, '', 0, 'id_form', '', '', '', NULL, 1, NULL),
(2, 1, 'Nombre Formulario', 'nombre_f', 30, 30, '\"obligatorio\":{\"mensaje\":\"Debes ingresar un nombre que identifique al formulario\"}', 2, NULL, 1, 'nombre_f', NULL, NULL, NULL, NULL, 1, NULL),
(3, 1, 'Query', 'query_f', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}', 3, NULL, 2, 'query_f', NULL, NULL, NULL, NULL, 1, NULL),
(4, 1, 'Clave Primaria', 'clave_primaria_f', 30, 30, '', 2, '', 1, 'clave_primaria_f', '', '', '', NULL, 1, NULL),
(5, 2, NULL, 'id_form', NULL, NULL, NULL, 1, NULL, 2, 'id_form', NULL, NULL, NULL, NULL, 1, NULL),
(6, 2, NULL, 'id_campo', NULL, 3, NULL, 1, NULL, 1, 'id_campo', NULL, NULL, NULL, NULL, 1, NULL),
(7, 2, 'Data', 'data_atributo', 100, 40, '', 3, '', 13, 'data_atributo', '', '', 'data-jidacontrol=\"Ingrese un PlaceHolder\"', NULL, 1, NULL),
(8, 2, 'Clase', 'class', 30, 50, NULL, 2, NULL, 17, 'class', NULL, NULL, NULL, NULL, 1, NULL),
(9, 2, 'ID Propiedad', 'id_propiedad', 30, 30, '\"obligatorio\":{\"mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}', 2, NULL, 4, 'id_propiedad', NULL, NULL, NULL, NULL, 1, NULL),
(10, 2, 'Orden', 'orden', 20, 20, '', 2, '', 18, 'orden', '', '', '', NULL, 1, NULL),
(11, 2, 'Opciones', 'opciones', NULL, NULL, '', 3, '', 9, 'opciones', '', '', '', '', 1, NULL),
(12, 2, 'Tipo de Control', 'control', NULL, NULL, NULL, 7, '=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion;9=Telefono', 5, 'control', NULL, NULL, NULL, NULL, 1, NULL),
(13, 2, 'Eventos', 'eventos', NULL, NULL, '', 3, '', 10, 'eventos', '', '', '', '', 1, NULL),
(14, 2, 'Size', 'size', 20, 20, '', 2, '', 8, 'size', '', '', '', '', 1, NULL),
(15, 2, 'Maxlength', 'maxlength', 20, 20, '', 2, '', 7, 'maxlength', '', '', '', '', 1, NULL),
(16, 2, 'Name Control', 'name', 30, 30, '\"obligatorio\":{\"mensaje\":\"El name del campo es obligatorio\"}', 2, NULL, 3, 'name', NULL, NULL, NULL, NULL, 1, NULL),
(17, 2, 'Label', 'label', 30, 100, '', 2, NULL, 6, 'label', NULL, NULL, NULL, NULL, 1, NULL),
(18, 2, 'Placeholder', 'placeholder', 100, 30, NULL, 2, NULL, 14, 'placeholder', 'hola mundo', NULL, NULL, NULL, 1, NULL),
(19, 2, 'Title', 'title', NULL, NULL, '', 2, '', 15, 'title', '', '', '', '', 1, NULL),
(20, 2, 'Visibilidad', 'visibilidad', NULL, NULL, NULL, 7, '=Seleccione...;1=Normal;2=Readonly;3=Disabled', 16, 'visibilidad', NULL, NULL, NULL, NULL, 1, NULL),
(21, 3, 'Nombre Usuario', 'nombre_usuario', 30, NULL, '\"obligatorio\":{\"Mensaje\":\"Debe ingresar su nombre de Usuario\"}', 2, NULL, 1, 'nombre_usuario_login', 'Nombre de usuario', NULL, NULL, NULL, 1, NULL),
(22, 3, 'Clave', 'clave_usuario', 30, NULL, '\"obligatorio\":{\"Mensaje\":\"Debe ingresar su clave\",}', 4, NULL, 2, 'clave_usuario_login', 'Clave Usuario', NULL, NULL, NULL, 1, NULL),
(23, 4, NULL, 'id_menu', NULL, NULL, NULL, 1, NULL, NULL, 'id_menu', NULL, NULL, NULL, NULL, 1, NULL),
(24, 4, 'Nombre Menu', 'nombre_menu', 100, 50, '\"obligatorio\":{\"mensaje\",\"Debe ingresar un nombre identificador del menu\"}', 2, NULL, 1, 'nombre_menu', NULL, NULL, NULL, NULL, 1, NULL),
(25, 5, NULL, 'id_opcion_menu', NULL, NULL, NULL, 1, NULL, NULL, 'id_opcion_menu', NULL, NULL, NULL, NULL, 1, NULL),
(26, 5, NULL, 'id_menu', NULL, NULL, NULL, 1, NULL, NULL, 'id_menu', NULL, NULL, NULL, NULL, 1, NULL),
(27, 5, 'URL', 'url_opcion', 100, 50, '\"programa\":{\"mensaje\":\"formato de url invalido \"}', 2, NULL, NULL, 'url_opcion', NULL, NULL, NULL, NULL, 1, NULL),
(28, 5, 'Nombre de opcion', 'nombre_opcion', 100, 50, '\"obligatorio\":{\"mensaje\":\"El nombre de la opcion es obligatorio\"}', 2, NULL, 2, 'nombre_opcion', 'Nombre a verse en el menu', NULL, NULL, 'nombre que se vera en el menu', 1, NULL),
(29, 5, 'Padre', 'padre', NULL, NULL, NULL, 7, '0=No Aplica;externo', NULL, 'padre', NULL, NULL, NULL, NULL, 1, NULL),
(30, 6, NULL, 'id_objeto', NULL, NULL, NULL, 1, NULL, NULL, 'id_objeto', NULL, NULL, NULL, NULL, 2, NULL),
(31, 6, 'Objeto', 'objeto', 50, 50, '\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}', 2, NULL, 0, 'objeto', NULL, NULL, NULL, NULL, 2, NULL),
(32, 7, NULL, 'id_objeto', NULL, NULL, NULL, 1, NULL, 0, 'id_objeto', NULL, NULL, NULL, NULL, 1, NULL),
(33, 7, 'Nombre del Objeto', 'objeto', 50, 50, '\"obligatorio\":\"mensaje\":\"El campo es obligatorio\"}', 2, NULL, 1, 'objeto', NULL, NULL, NULL, NULL, 1, NULL),
(34, 7, 'Componente', 'id_componente', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"debe seleccionar el componente al que pertenece el objeto\"}', 7, '=Seleccione;Select id_componente,componente from s_componentes order by id_componente', 3, 'id_componente', NULL, NULL, NULL, NULL, 1, NULL),
(35, 8, NULL, 'id_componente', NULL, NULL, NULL, 1, NULL, 0, 'id_componente', NULL, NULL, NULL, NULL, 1, NULL),
(36, 8, 'Componente', 'componente', 50, 50, '\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}', 2, NULL, 1, 'componente', NULL, NULL, NULL, NULL, 1, NULL),
(37, 9, NULL, 'id_metodo', NULL, NULL, NULL, 1, NULL, 0, 'id_metodo', NULL, NULL, NULL, NULL, 1, NULL),
(38, 9, 'Perfiles', 'id_perfil', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"Debe asignar algun perfil\"}', 5, 'Select id_perfil,perfil from s_perfiles', 1, 'perfil', NULL, NULL, NULL, NULL, 1, NULL),
(39, 10, 'Perfiles de Acceso', 'id_perfil', NULL, NULL, NULL, 5, 'select id_perfil,perfil from s_perfiles', 1, 'perfil', NULL, NULL, NULL, NULL, 1, NULL),
(40, 11, 'Perfil', 'id_perfil', NULL, NULL, NULL, 5, 'Select id_perfil,perfil from s_perfiles', 2, 'perfil', NULL, NULL, NULL, NULL, 1, NULL),
(41, 12, 'Perfil', 'id_perfil', NULL, NULL, NULL, 5, 'select id_perfil, perfil from s_perfiles', NULL, 'perfil', NULL, NULL, NULL, NULL, 1, NULL),
(42, 1, 'Estructura', 'estructura', 50, 50, NULL, 2, NULL, 4, 'estructura', 'Estructura', NULL, NULL, 'Estructura de creacion del formulario', 1, NULL),
(43, 2, 'Clave', 'clave_evento', 40, 40, NULL, 2, NULL, 11, 'clave_evento', NULL, NULL, NULL, NULL, 1, NULL),
(44, 2, 'Valor Evento', 'valor_evento', 40, 40, NULL, NULL, NULL, 12, 'valor_evento', NULL, NULL, NULL, NULL, 1, NULL),
(45, 13, NULL, 'id_perfil', NULL, NULL, NULL, 2, NULL, NULL, 'perfil', NULL, NULL, NULL, NULL, 1, NULL),
(46, 13, NULL, 'nombre_perfil', NULL, NULL, NULL, 2, NULL, NULL, 'nombre_perfil', NULL, NULL, NULL, NULL, 1, NULL),
(47, 13, NULL, 'fecha_creado', NULL, NULL, NULL, 2, NULL, NULL, 'fecha_creado', NULL, NULL, NULL, NULL, 1, NULL),
(48, 13, NULL, 'clave_perfil', NULL, NULL, NULL, 2, NULL, NULL, 'clave_perfil', NULL, NULL, NULL, NULL, 1, NULL),
(173, 5, 'Orden', 'orden', NULL, NULL, '\"numerico\":{\"mensaje\":\"El orden debe ser numerico\"}', 2, NULL, NULL, 'orden', NULL, NULL, NULL, NULL, 1, NULL),
(174, 5, 'Estatus', 'id_estatus', NULL, NULL, NULL, 7, 'select * from s_estatus where  id_estatus in(1,2)', NULL, 'id_estatus', NULL, NULL, NULL, NULL, 1, NULL),
(175, 5, 'Icono', 'icono', NULL, NULL, NULL, NULL, NULL, NULL, 'icono', 'clase css fuente o url de imagen', NULL, NULL, NULL, 1, NULL),
(176, 5, 'Selector del Icono', 'selector_icono', NULL, NULL, NULL, 7, '1=Span;2=Imagen', NULL, 'selector_icono', NULL, NULL, NULL, NULL, 1, NULL),
(177, 14, NULL, 'id_usuario', NULL, NULL, NULL, 1, NULL, 1, 'id_usuario', NULL, NULL, NULL, NULL, 1, NULL),
(178, 14, 'Clave', 'clave_usuario', 50, NULL, '\"obligatorio\":{\"mensaje\":\"Debe ingresar una clave para el usuario\"}', 4, NULL, 5, 'clave_usuario', 'Clave', NULL, NULL, NULL, 1, NULL),
(179, 14, 'Nombres', 'nombres', NULL, NULL, NULL, 2, NULL, 2, 'nombres', 'Nombres', NULL, NULL, NULL, 1, NULL),
(180, 14, 'Apellidos', 'apellidos', NULL, NULL, NULL, 2, NULL, 3, 'apellidos', 'Apellidos', NULL, NULL, NULL, 1, NULL),
(181, 14, 'Nombre de Usuario', 'nombre_usuario', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"Debe ingresar un nombre de usuario o nickname\"}', NULL, NULL, 4, 'nombre_usuario', 'Nombre de Usuario', NULL, NULL, NULL, 1, NULL),
(182, 14, 'Correo El&eacute;ctronico', 'correo', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"Debe ingresar el correo del usuario\"},\"email\":{\"mensaje\":\"El formato del correo es incorrecto\"}', NULL, NULL, 6, 'correo', NULL, NULL, NULL, NULL, 1, NULL),
(183, 14, 'Estatus', 'id_estatus', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"Debe seleccionar el estatus\"},\"numerico\":{\"El id del estatus debe ser numerico\"}', 7, 'select id_estatus,estatus from s_estatus where id_estatus in (1,2) order by id_estatus', 7, 'id_estatus', NULL, NULL, NULL, NULL, 1, NULL),
(184, 6, 'Descripcion', 'descripcion', 100, NULL, '\"alfanumerico\":{\"mensaje\":\"La descripci&oacute;n del objeto debe ser alfanumerica\"}', NULL, NULL, 3, 'descripcion', NULL, NULL, NULL, NULL, 1, NULL),
(185, 5, 'Perfiles Con Acceso', 'id_perfil', NULL, NULL, '\"obligatorio\":{\"mensaje\":\"Debe Seleccionar Un Perfil\"}', 5, 'select id_perfil, perfil from s_perfiles', 10, 'id_perfil', NULL, NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_jida_formularios`
--

CREATE TABLE `s_jida_formularios` (
  `id_form` int(11) NOT NULL,
  `nombre_f` varchar(80) NOT NULL,
  `query_f` text NOT NULL,
  `clave_primaria_f` varchar(45) DEFAULT NULL,
  `nombre_identificador` varchar(100) NOT NULL,
  `estructura` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_jida_formularios`
--

INSERT INTO `s_jida_formularios` (`id_form`, `nombre_f`, `query_f`, `clave_primaria_f`, `nombre_identificador`, `estructura`) VALUES
(1, 'formularios', 'select id_form,nombre_f,query_f,clave_primaria_f,estructura from s_jida_formularios', 'id_form', 'Formularios', NULL),
(2, 'Campos Formulario', 'select id_campo, id_form, label, name, maxlength, size,\n eventos, 1 clave_evento, 2 valor_evento, control,  opciones, orden, id_propiedad, placeholder,\n class, data_atributo, title, visibilidad from s_jida_campos_f', 'id_campo', 'CamposFormulario', '2;3;1;2;1x2;2;1x3;3'),
(3, 'Login', 'select nombre_usuario,clave_usuario from s_usuarios', NULL, 'Login', NULL),
(4, 'Procesar menus', 'select * from s_menus', 'id_menu', 'ProcesarMenus', NULL),
(5, 'Procesar opcion menu', 'select a.id_opcion_menu,id_menu,url_opcion,nombre_opcion,icono,orden, selector_icono,id_estatus, padre, id_perfil from s_opciones_menu a\n left join s_opciones_menu_perfiles b on (a.id_opcion_menu=b.id_opcion_menu)', 'a.id_opcion_menu', 'ProcesarOpcionMenu', NULL),
(6, 'sistema objetos', 'select id_objeto,objeto,descripcion from s_objetos', 'id_objeto', 'SistemaObjetos', NULL),
(7, 'Registro Objetos', 'select id_objeto,objeto,id_componente from s_objetos', 'id_objeto', 'RegistroObjetos', NULL),
(8, 'Componente', 'select id_componente,componente from s_componentes', 'id_componente', 'Componente', NULL),
(9, 'Perfiles a metodos', 'select id_metodo,id_perfil from s_metodos_perfiles', 'id_metodo', 'PerfilesAMetodos', NULL),
(10, 'Perfiles a objetos', 'select id_perfil from s_objetos_perfiles', 'id_objeto', 'PerfilesAObjetos', NULL),
(11, 'Perfiles a componentes', 'select id_perfil from s_componentes_perfiles', 'id_componente', 'PerfilesAComponentes', NULL),
(12, 'perfiles a usuario', 'select id_perfil from s_usuarios_perfiles', 'id_usuario', 'PerfilesAUsuario', NULL),
(13, 'Perfiles', 'select * from s_perfiles', 'id_perfil', 'Perfiles', NULL),
(14, 'registro Usuarios', 'select id_usuario,nombre_usuario,clave_usuario,nombres,apellidos,correo,id_estatus from s_usuarios', 'id_usuario', 'RegistroUsuarios', NULL),
(15, 'Descripcion Metodo', 'select descripcion from s_metodos', 'id_metodo', 'DescripcionMetodo', '1'),
(16, 'Descripcion Objeto', 'select descripcion from s_objetos', 'id_objeto', 'DescripcionObjeto', '1');

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
-- Estructura de tabla para la tabla `s_metodos`
--

CREATE TABLE `s_metodos` (
  `id_metodo` int(11) NOT NULL,
  `id_objeto` int(11) DEFAULT NULL,
  `metodo` varchar(150) DEFAULT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `loggin` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_metodos`
--

INSERT INTO `s_metodos` (`id_metodo`, `id_objeto`, `metodo`, `descripcion`, `loggin`) VALUES
(38, 22, 'index', NULL, 0),
(39, 23, 'index', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_metodos_perfiles`
--

CREATE TABLE `s_metodos_perfiles` (
  `id_metodo_perfil` int(11) NOT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_objetos`
--

CREATE TABLE `s_objetos` (
  `id_objeto` int(11) NOT NULL,
  `id_componente` int(11) DEFAULT NULL,
  `objeto` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_objetos`
--

INSERT INTO `s_objetos` (`id_objeto`, `id_componente`, `objeto`, `descripcion`) VALUES
(22, 2, 'Jadmin', NULL),
(23, 3, 'Admin', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_objetos_perfiles`
--

CREATE TABLE `s_objetos_perfiles` (
  `id_objeto_perfil` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_objeto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_opciones_menu_perfiles`
--

CREATE TABLE `s_opciones_menu_perfiles` (
  `id_opcion_menu_perfil` int(11) NOT NULL,
  `id_opcion_menu` int(11) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_opciones_menu_perfiles`
--

INSERT INTO `s_opciones_menu_perfiles` (`id_opcion_menu_perfil`, `id_opcion_menu`, `id_perfil`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 9, 1),
(7, 10, 1),
(8, 11, 1),
(9, 27, 1),
(10, 28, 1),
(11, 29, 1),
(12, 30, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_perfiles`
--

CREATE TABLE `s_perfiles` (
  `id_perfil` int(11) NOT NULL,
  `perfil` varchar(50) DEFAULT NULL,
  `fecha_creado` datetime DEFAULT NULL,
  `clave_perfil` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_perfiles`
--

INSERT INTO `s_perfiles` (`id_perfil`, `perfil`, `fecha_creado`, `clave_perfil`) VALUES
(1, 'Jida Administrador', '2014-02-13 13:01:11', 'JidaAdministrador'),
(2, 'Administrador', '2014-02-13 13:01:11', 'Administrador'),
(3, 'Usuario Publico', '2014-02-13 13:01:11', 'UsuarioPublico'),
(4, 'Cliente', '2014-03-16 07:51:20', 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_usuarios`
--

CREATE TABLE `s_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `clave_usuario` varchar(50) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `id_estatus` int(11) NOT NULL,
  `ultima_session` datetime DEFAULT NULL,
  `validacion` varchar(500) DEFAULT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `codigo_recuperacion` varchar(80) DEFAULT NULL,
  `sexo` int(11) DEFAULT NULL,
  `img_perfil` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_usuarios`
--

INSERT INTO `s_usuarios` (`id_usuario`, `nombre_usuario`, `clave_usuario`, `fecha_creacion`, `fecha_modificacion`, `activo`, `id_estatus`, `ultima_session`, `validacion`, `nombres`, `apellidos`, `correo`, `codigo_recuperacion`, `sexo`, `img_perfil`) VALUES
(1, 'jadmin', '3711be79067177199efb2589054a6894', '2014-02-13 13:01:12', NULL, 1, 1, '2014-06-06 17:09:55', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'jeanpierre', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 1, 1, NULL, '1', NULL, NULL, 'jeacontreras2009@gmail.com', NULL, NULL, NULL),
(3, 'felix', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 1, 1, '2017-03-10 14:01:46', '1', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'dayan', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 1, 1, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `s_usuarios_perfiles`
--

CREATE TABLE `s_usuarios_perfiles` (
  `id_usuario_perfil` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `s_usuarios_perfiles`
--

INSERT INTO `s_usuarios_perfiles` (`id_usuario_perfil`, `id_usuario`, `id_perfil`) VALUES
(11, 1, 1),
(12, 1, 2),
(23, 2, 1),
(24, 2, 2),
(25, 3, 1),
(26, 3, 2),
(27, 4, 1),
(28, 4, 2);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vj_acceso_componentes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vj_acceso_componentes` (
`id_componente_perfil` int(11)
,`id_perfil` int(11)
,`clave_perfil` varchar(100)
,`id_componente` int(11)
,`componente` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vj_acceso_metodos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vj_acceso_metodos` (
`id_metodo` int(11)
,`id_objeto` int(11)
,`objeto` varchar(100)
,`metodo` varchar(150)
,`loggin` int(11)
,`id_perfil` int(11)
,`clave_perfil` varchar(100)
,`perfil` varchar(50)
,`id_componente` int(11)
,`componente` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vj_acceso_objetos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vj_acceso_objetos` (
`id_objeto_perfil` int(11)
,`id_perfil` int(11)
,`clave_perfil` varchar(100)
,`nombre_perfil` varchar(50)
,`id_objeto` int(11)
,`objeto` varchar(100)
,`id_componente` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vj_perfiles_usuario`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vj_perfiles_usuario` (
`id_usuario_perfil` int(11)
,`id_perfil` int(11)
,`id_usuario` int(11)
,`nombre_usuario` varchar(100)
,`nombres` varchar(100)
,`apellidos` varchar(100)
,`clave_perfil` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vj_perfiles_usuarios`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vj_perfiles_usuarios` (
`id_usuario_perfil` int(11)
,`id_perfil` int(11)
,`id_usuario` int(11)
,`nombre_usuario` varchar(100)
,`nombres` varchar(100)
,`apellidos` varchar(100)
,`clave_perfil` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vj_acceso_componentes`
--
DROP TABLE IF EXISTS `vj_acceso_componentes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vj_acceso_componentes`  AS  select `a`.`id_componente_perfil` AS `id_componente_perfil`,`a`.`id_perfil` AS `id_perfil`,`b`.`clave_perfil` AS `clave_perfil`,`a`.`id_componente` AS `id_componente`,`c`.`componente` AS `componente` from ((`s_componentes_perfiles` `a` join `s_perfiles` `b` on((`a`.`id_perfil` = `b`.`id_perfil`))) join `s_componentes` `c` on((`c`.`id_componente` = `a`.`id_componente`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vj_acceso_metodos`
--
DROP TABLE IF EXISTS `vj_acceso_metodos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vj_acceso_metodos`  AS  select `a`.`id_metodo` AS `id_metodo`,`a`.`id_objeto` AS `id_objeto`,`d`.`objeto` AS `objeto`,`a`.`metodo` AS `metodo`,`a`.`loggin` AS `loggin`,`b`.`id_perfil` AS `id_perfil`,`c`.`clave_perfil` AS `clave_perfil`,`c`.`perfil` AS `perfil`,`e`.`id_componente` AS `id_componente`,`e`.`componente` AS `componente` from ((((`s_metodos` `a` left join `s_metodos_perfiles` `b` on((`a`.`id_metodo` = `b`.`id_metodo`))) left join `s_perfiles` `c` on((`c`.`id_perfil` = `b`.`id_perfil`))) join `s_objetos` `d` on((`a`.`id_objeto` = `d`.`id_objeto`))) join `s_componentes` `e` on((`d`.`id_componente` = `e`.`id_componente`))) where ((`b`.`id_perfil` is not null) or (`a`.`loggin` = 0)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vj_acceso_objetos`
--
DROP TABLE IF EXISTS `vj_acceso_objetos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vj_acceso_objetos`  AS  select `a`.`id_objeto_perfil` AS `id_objeto_perfil`,`a`.`id_perfil` AS `id_perfil`,`c`.`clave_perfil` AS `clave_perfil`,`c`.`perfil` AS `nombre_perfil`,`a`.`id_objeto` AS `id_objeto`,`b`.`objeto` AS `objeto`,`b`.`id_componente` AS `id_componente` from ((`s_objetos_perfiles` `a` join `s_objetos` `b` on((`b`.`id_objeto` = `a`.`id_objeto`))) join `s_perfiles` `c` on((`c`.`id_perfil` = `a`.`id_perfil`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vj_perfiles_usuario`
--
DROP TABLE IF EXISTS `vj_perfiles_usuario`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vj_perfiles_usuario`  AS  select `a`.`id_usuario_perfil` AS `id_usuario_perfil`,`a`.`id_perfil` AS `id_perfil`,`a`.`id_usuario` AS `id_usuario`,`c`.`nombre_usuario` AS `nombre_usuario`,`c`.`nombres` AS `nombres`,`c`.`apellidos` AS `apellidos`,`b`.`clave_perfil` AS `clave_perfil` from ((`s_usuarios_perfiles` `a` join `s_perfiles` `b` on((`a`.`id_perfil` = `b`.`id_perfil`))) join `s_usuarios` `c` on((`a`.`id_usuario` = `c`.`id_usuario`))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vj_perfiles_usuarios`
--
DROP TABLE IF EXISTS `vj_perfiles_usuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vj_perfiles_usuarios`  AS  select `a`.`id_usuario_perfil` AS `id_usuario_perfil`,`a`.`id_perfil` AS `id_perfil`,`a`.`id_usuario` AS `id_usuario`,`c`.`nombre_usuario` AS `nombre_usuario`,`c`.`nombres` AS `nombres`,`c`.`apellidos` AS `apellidos`,`b`.`clave_perfil` AS `clave_perfil` from ((`s_usuarios_perfiles` `a` join `s_perfiles` `b` on((`a`.`id_perfil` = `b`.`id_perfil`))) join `s_usuarios` `c` on((`a`.`id_usuario` = `c`.`id_usuario`))) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `s_campos_f`
--
ALTER TABLE `s_campos_f`
  ADD PRIMARY KEY (`id_campo`),
  ADD KEY `id_form` (`id_form`);

--
-- Indices de la tabla `s_componentes`
--
ALTER TABLE `s_componentes`
  ADD PRIMARY KEY (`id_componente`);

--
-- Indices de la tabla `s_componentes_perfiles`
--
ALTER TABLE `s_componentes_perfiles`
  ADD PRIMARY KEY (`id_componente_perfil`),
  ADD KEY `id_perfil` (`id_perfil`),
  ADD KEY `id_componente` (`id_componente`);

--
-- Indices de la tabla `s_estatus`
--
ALTER TABLE `s_estatus`
  ADD PRIMARY KEY (`id_estatus`);

--
-- Indices de la tabla `s_formularios`
--
ALTER TABLE `s_formularios`
  ADD PRIMARY KEY (`id_form`);

--
-- Indices de la tabla `s_jida_campos_f`
--
ALTER TABLE `s_jida_campos_f`
  ADD PRIMARY KEY (`id_campo`),
  ADD KEY `id_form` (`id_form`);

--
-- Indices de la tabla `s_jida_formularios`
--
ALTER TABLE `s_jida_formularios`
  ADD PRIMARY KEY (`id_form`);

--
-- Indices de la tabla `s_menus`
--
ALTER TABLE `s_menus`
  ADD PRIMARY KEY (`id_menu`);

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
  ADD KEY `id_menu` (`id_menu`);

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
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `s_usuarios`
--
ALTER TABLE `s_usuarios`
  ADD PRIMARY KEY (`id_usuario`);

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
-- AUTO_INCREMENT de la tabla `s_campos_f`
--
ALTER TABLE `s_campos_f`
  MODIFY `id_campo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT de la tabla `s_componentes`
--
ALTER TABLE `s_componentes`
  MODIFY `id_componente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `s_componentes_perfiles`
--
ALTER TABLE `s_componentes_perfiles`
  MODIFY `id_componente_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `s_estatus`
--
ALTER TABLE `s_estatus`
  MODIFY `id_estatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `s_formularios`
--
ALTER TABLE `s_formularios`
  MODIFY `id_form` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT de la tabla `s_jida_campos_f`
--
ALTER TABLE `s_jida_campos_f`
  MODIFY `id_campo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;
--
-- AUTO_INCREMENT de la tabla `s_jida_formularios`
--
ALTER TABLE `s_jida_formularios`
  MODIFY `id_form` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
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
-- AUTO_INCREMENT de la tabla `s_objetos_perfiles`
--
ALTER TABLE `s_objetos_perfiles`
  MODIFY `id_objeto_perfil` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `s_opciones_menu`
--
ALTER TABLE `s_opciones_menu`
  MODIFY `id_opcion_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT de la tabla `s_opciones_menu_perfiles`
--
ALTER TABLE `s_opciones_menu_perfiles`
  MODIFY `id_opcion_menu_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `s_perfiles`
--
ALTER TABLE `s_perfiles`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `s_usuarios`
--
ALTER TABLE `s_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `s_usuarios_perfiles`
--
ALTER TABLE `s_usuarios_perfiles`
  MODIFY `id_usuario_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `s_campos_f`
--
ALTER TABLE `s_campos_f`
  ADD CONSTRAINT `s_campos_f_ibfk_1` FOREIGN KEY (`id_form`) REFERENCES `s_formularios` (`id_form`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_componentes_perfiles`
--
ALTER TABLE `s_componentes_perfiles`
  ADD CONSTRAINT `s_componentes_perfiles_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_componentes_perfiles_ibfk_2` FOREIGN KEY (`id_componente`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_jida_campos_f`
--
ALTER TABLE `s_jida_campos_f`
  ADD CONSTRAINT `s_jida_campos_f_ibfk_1` FOREIGN KEY (`id_form`) REFERENCES `s_jida_formularios` (`id_form`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `s_objetos_perfiles`
--
ALTER TABLE `s_objetos_perfiles`
  ADD CONSTRAINT `s_objetos_perfiles_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_objetos_perfiles_ibfk_2` FOREIGN KEY (`id_objeto`) REFERENCES `s_objetos` (`id_objeto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_opciones_menu`
--
ALTER TABLE `s_opciones_menu`
  ADD CONSTRAINT `s_opciones_menu_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_opciones_menu_perfiles`
--
ALTER TABLE `s_opciones_menu_perfiles`
  ADD CONSTRAINT `s_opciones_menu_perfiles_ibfk_1` FOREIGN KEY (`id_opcion_menu`) REFERENCES `s_opciones_menu` (`id_opcion_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_opciones_menu_perfiles_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `s_usuarios_perfiles`
--
ALTER TABLE `s_usuarios_perfiles`
  ADD CONSTRAINT `s_usuarios_perfiles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `s_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `s_usuarios_perfiles_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
