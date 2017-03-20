-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: laurafidalgo
-- ------------------------------------------------------
-- Server version	5.7.13-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `s_campos_f`
--

DROP TABLE IF EXISTS `s_campos_f`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_campos_f` (
  `id_campo` int(11) NOT NULL AUTO_INCREMENT,
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
  `ayuda` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_campo`),
  KEY `id_form` (`id_form`),
  CONSTRAINT `s_campos_f_ibfk_1` FOREIGN KEY (`id_form`) REFERENCES `s_formularios` (`id_form`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_campos_f`
--

LOCK TABLES `s_campos_f` WRITE;
/*!40000 ALTER TABLE `s_campos_f` DISABLE KEYS */;
INSERT INTO `s_campos_f` VALUES (1,1,'','id_form',NULL,NULL,'',1,'',0,'id_form','','','',NULL,1,NULL),(2,1,'Nombre Formulario','nombre_f',30,30,'\"obligatorio\":{\"mensaje\":\"Debes ingresar un nombre que identifique al formulario\"}',2,NULL,1,'nombre_f',NULL,NULL,NULL,NULL,1,NULL),(3,1,'Query','query_f',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}',3,NULL,2,'query_f',NULL,NULL,NULL,NULL,1,NULL),(4,1,'Clave Primaria','clave_primaria_f',30,30,'',2,'',1,'clave_primaria_f','','','',NULL,1,NULL),(5,2,'a','id_form',NULL,NULL,NULL,1,NULL,2,'id_form',NULL,NULL,NULL,NULL,1,NULL),(6,2,NULL,'id_campo',NULL,3,NULL,1,NULL,1,'id_campo',NULL,NULL,NULL,NULL,1,NULL),(7,2,'Data','data_atributo',100,40,'',3,'',13,'data_atributo','','','data-jidacontrol=\"Ingrese un PlaceHolder\"',NULL,1,NULL),(8,2,'Clase','class',30,50,NULL,2,NULL,17,'class',NULL,NULL,NULL,NULL,1,NULL),(9,2,'ID Propiedad','id_propiedad',30,30,'\"obligatorio\":{\"Mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}',2,NULL,5,'id_propiedad',NULL,NULL,NULL,NULL,1,NULL),(10,2,'Orden','orden',20,20,'',2,'',18,'orden','','','',NULL,1,NULL),(11,2,'Opciones','opciones',NULL,NULL,'',3,'',9,'opciones','','','','',1,NULL),(12,2,'Tipo de Control','control',NULL,NULL,NULL,7,'=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion;9=Telefono',5,'control',NULL,NULL,NULL,NULL,1,NULL),(13,2,'Eventos','eventos',NULL,NULL,'',3,'',10,'eventos','','','','',1,NULL),(14,2,'Size','size',20,20,'',2,'',8,'size','','','','',1,NULL),(15,2,'Maxlength','maxlength',20,20,'',2,'',7,'maxlength','','','','',1,NULL),(16,2,'Name','name',30,30,'\"obligatorio\":{\"Mensaje\":\"El campo debe llevar un nombre\"},\"programa\":{\"Mensaje\":\"El nombre no es valido\"}',2,'',3,'name','','','','',1,NULL),(17,2,'Label','label',30,100,NULL,2,NULL,6,'label',NULL,NULL,NULL,NULL,1,NULL),(18,2,'Placeholder','placeholder',100,30,NULL,2,NULL,14,'placeholder','hola mundo',NULL,NULL,NULL,1,NULL),(19,2,'Title','title',NULL,NULL,'',2,'',15,'title','','','','',1,NULL),(20,2,'Visibilidad','visibilidad',NULL,NULL,NULL,7,'=Seleccione...;1=Normal;2=Readonly;3=Disabled',16,'visibilidad',NULL,NULL,NULL,NULL,1,NULL),(42,1,'Estructura','estructura',50,50,NULL,2,NULL,4,'estructura','Estructura',NULL,NULL,'Estructura de creacion del formulario',1,NULL),(43,2,'Clave','clave_evento',40,40,NULL,2,NULL,11,'clave_evento',NULL,NULL,NULL,NULL,1,NULL),(44,2,'Valor Evento','valor_evento',40,40,NULL,NULL,NULL,12,'valor_evento',NULL,NULL,NULL,NULL,1,NULL),(49,14,NULL,'id_campo',NULL,NULL,NULL,2,NULL,NULL,'id_campo',NULL,NULL,NULL,NULL,1,NULL),(50,14,NULL,'id_form',NULL,NULL,NULL,2,NULL,NULL,'id_form',NULL,NULL,NULL,NULL,1,NULL),(51,14,NULL,'label',NULL,NULL,NULL,2,NULL,NULL,'label',NULL,NULL,NULL,NULL,1,NULL),(52,14,NULL,'name',NULL,NULL,NULL,2,NULL,NULL,'name',NULL,NULL,NULL,NULL,1,NULL),(53,14,NULL,'maxlength',NULL,NULL,NULL,2,NULL,NULL,'maxlength',NULL,NULL,NULL,NULL,1,NULL),(54,14,NULL,'size',NULL,NULL,NULL,2,NULL,NULL,'size',NULL,NULL,NULL,NULL,1,NULL),(55,14,NULL,'eventos',NULL,NULL,NULL,2,NULL,NULL,'eventos',NULL,NULL,NULL,NULL,1,NULL),(56,14,NULL,'control',NULL,NULL,NULL,2,NULL,NULL,'control',NULL,NULL,NULL,NULL,1,NULL),(57,14,NULL,'opciones',NULL,NULL,NULL,2,NULL,NULL,'opciones',NULL,NULL,NULL,NULL,1,NULL),(58,14,NULL,'orden',NULL,NULL,NULL,2,NULL,NULL,'orden',NULL,NULL,NULL,NULL,1,NULL),(59,14,NULL,'id_propiedad',NULL,NULL,NULL,2,NULL,NULL,'id_propiedad',NULL,NULL,NULL,NULL,1,NULL),(60,14,NULL,'placeholder',NULL,NULL,NULL,2,NULL,NULL,'placeholder',NULL,NULL,NULL,NULL,1,NULL),(61,14,NULL,'class',NULL,NULL,NULL,2,NULL,NULL,'class',NULL,NULL,NULL,NULL,1,NULL),(62,14,NULL,'data_atributo',NULL,NULL,NULL,2,NULL,NULL,'data_atributo',NULL,NULL,NULL,NULL,1,NULL),(63,14,'testing nor','title',NULL,NULL,NULL,2,'a',NULL,'title',NULL,NULL,NULL,NULL,1,NULL),(64,14,NULL,'visibilidad',NULL,NULL,NULL,2,NULL,NULL,'visibilidad',NULL,NULL,NULL,NULL,1,NULL),(65,14,NULL,'ayuda',NULL,NULL,NULL,2,NULL,NULL,'ayuda',NULL,NULL,NULL,NULL,1,NULL),(66,15,'Como estas papa','algo',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"algo para que se vea\"}',2,NULL,NULL,'algo',NULL,NULL,NULL,NULL,1,NULL),(67,15,NULL,'como',NULL,NULL,NULL,2,NULL,NULL,'como',NULL,NULL,NULL,NULL,1,NULL),(68,15,NULL,'algo_mas',NULL,NULL,NULL,2,NULL,NULL,'algo_mas',NULL,NULL,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `s_campos_f` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_componentes`
--

DROP TABLE IF EXISTS `s_componentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_componentes` (
  `id_componente` int(11) NOT NULL AUTO_INCREMENT,
  `componente` varchar(100) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_componente`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_componentes`
--

LOCK TABLES `s_componentes` WRITE;
/*!40000 ALTER TABLE `s_componentes` DISABLE KEYS */;
INSERT INTO `s_componentes` VALUES (1,'principal',NULL),(2,'jadmin',NULL),(3,'admin',NULL);
/*!40000 ALTER TABLE `s_componentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_componentes_perfiles`
--

DROP TABLE IF EXISTS `s_componentes_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_componentes_perfiles` (
  `id_componente_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `id_componente` int(11) NOT NULL,
  PRIMARY KEY (`id_componente_perfil`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_componente` (`id_componente`),
  CONSTRAINT `s_componentes_perfiles_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_componentes_perfiles_ibfk_2` FOREIGN KEY (`id_componente`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_componentes_perfiles`
--

LOCK TABLES `s_componentes_perfiles` WRITE;
/*!40000 ALTER TABLE `s_componentes_perfiles` DISABLE KEYS */;
INSERT INTO `s_componentes_perfiles` VALUES (4,1,2),(11,1,3),(12,2,3),(13,1,1),(14,2,1);
/*!40000 ALTER TABLE `s_componentes_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_elementos`
--

DROP TABLE IF EXISTS `s_elementos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_elementos` (
  `id_elemento` int(11) NOT NULL,
  `elemento` varchar(50) DEFAULT NULL,
  `data` text,
  `area` varchar(80) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_elemento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_elementos`
--

LOCK TABLES `s_elementos` WRITE;
/*!40000 ALTER TABLE `s_elementos` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_elementos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_estatus`
--

DROP TABLE IF EXISTS `s_estatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_estatus` (
  `id_estatus` int(11) NOT NULL AUTO_INCREMENT,
  `estatus` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_estatus`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_estatus`
--

LOCK TABLES `s_estatus` WRITE;
/*!40000 ALTER TABLE `s_estatus` DISABLE KEYS */;
INSERT INTO `s_estatus` VALUES (1,'Activo'),(2,'Inactivo'),(3,'Eliminado'),(4,'Data Incompleta');
/*!40000 ALTER TABLE `s_estatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_formularios`
--

DROP TABLE IF EXISTS `s_formularios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_formularios` (
  `id_form` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_f` varchar(80) NOT NULL,
  `query_f` text NOT NULL,
  `clave_primaria_f` varchar(45) DEFAULT NULL,
  `nombre_identificador` varchar(100) NOT NULL,
  `estructura` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_form`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_formularios`
--

LOCK TABLES `s_formularios` WRITE;
/*!40000 ALTER TABLE `s_formularios` DISABLE KEYS */;
INSERT INTO `s_formularios` VALUES (1,'formularios','select id_form,nombre_f,query_f,clave_primaria_f,estructura from s_formularios','id_form','Formularios',NULL),(2,'Campos Formulario','select id_campo, id_form, label, name, maxlength, size,\r\neventos, 1 clave_evento, 2 valor_evento, control,  opciones, orden, id_propiedad, placeholder,\r\nclass, data_atributo, title, visibilidad from s_campos_f','id_campo','CamposFormulario','2;3;1;2;1x2;2;1x3;3'),(14,'testing','select * from s_campos_f','id_campo','Testing',NULL),(15,'Formulario de prueba','select 1 as algo, 2 as como, 3 as algo_mas from s_campos_f',NULL,'FormularioDePrueba',NULL);
/*!40000 ALTER TABLE `s_formularios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_jida_campos_f`
--

DROP TABLE IF EXISTS `s_jida_campos_f`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_jida_campos_f` (
  `id_campo` int(11) NOT NULL AUTO_INCREMENT,
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
  `ayuda` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_campo`),
  KEY `id_form` (`id_form`),
  CONSTRAINT `s_jida_campos_f_ibfk_1` FOREIGN KEY (`id_form`) REFERENCES `s_jida_formularios` (`id_form`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=186 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_jida_campos_f`
--

LOCK TABLES `s_jida_campos_f` WRITE;
/*!40000 ALTER TABLE `s_jida_campos_f` DISABLE KEYS */;
INSERT INTO `s_jida_campos_f` VALUES (1,1,'','id_form',NULL,NULL,'',1,'',0,'id_form','','','',NULL,1,NULL),(2,1,'Nombre Formulario','nombre_f',30,30,'\"obligatorio\":{\"mensaje\":\"Debes ingresar un nombre que identifique al formulario\"}',2,NULL,1,'nombre_f',NULL,NULL,NULL,NULL,1,NULL),(3,1,'Query','query_f',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}',3,NULL,2,'query_f',NULL,NULL,NULL,NULL,1,NULL),(4,1,'Clave Primaria','clave_primaria_f',30,30,'',2,'',1,'clave_primaria_f','','','',NULL,1,NULL),(5,2,NULL,'id_form',NULL,NULL,NULL,1,NULL,2,'id_form',NULL,NULL,NULL,NULL,1,NULL),(6,2,NULL,'id_campo',NULL,3,NULL,1,NULL,1,'id_campo',NULL,NULL,NULL,NULL,1,NULL),(7,2,'Data','data_atributo',100,40,'',3,'',13,'data_atributo','','','data-jidacontrol=\"Ingrese un PlaceHolder\"',NULL,1,NULL),(8,2,'Clase','class',30,50,NULL,2,NULL,17,'class',NULL,NULL,NULL,NULL,1,NULL),(9,2,'ID Propiedad','id_propiedad',30,30,'\"obligatorio\":{\"mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}',2,NULL,4,'id_propiedad',NULL,NULL,NULL,NULL,1,NULL),(10,2,'Orden','orden',20,20,'',2,'',18,'orden','','','',NULL,1,NULL),(11,2,'Opciones','opciones',NULL,NULL,'',3,'',9,'opciones','','','','',1,NULL),(12,2,'Tipo de Control','control',NULL,NULL,NULL,7,'=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion;9=Telefono',5,'control',NULL,NULL,NULL,NULL,1,NULL),(13,2,'Eventos','eventos',NULL,NULL,'',3,'',10,'eventos','','','','',1,NULL),(14,2,'Size','size',20,20,'',2,'',8,'size','','','','',1,NULL),(15,2,'Maxlength','maxlength',20,20,'',2,'',7,'maxlength','','','','',1,NULL),(16,2,'Name Control','name',30,30,'\"obligatorio\":{\"mensaje\":\"El name del campo es obligatorio\"}',2,NULL,3,'name',NULL,NULL,NULL,NULL,1,NULL),(17,2,'Label','label',30,100,'',2,NULL,6,'label',NULL,NULL,NULL,NULL,1,NULL),(18,2,'Placeholder','placeholder',100,30,NULL,2,NULL,14,'placeholder','hola mundo',NULL,NULL,NULL,1,NULL),(19,2,'Title','title',NULL,NULL,'',2,'',15,'title','','','','',1,NULL),(20,2,'Visibilidad','visibilidad',NULL,NULL,NULL,7,'=Seleccione...;1=Normal;2=Readonly;3=Disabled',16,'visibilidad',NULL,NULL,NULL,NULL,1,NULL),(21,3,'Nombre Usuario','nombre_usuario',30,NULL,'\"obligatorio\":{\"Mensaje\":\"Debe ingresar su nombre de Usuario\"}',2,NULL,1,'nombre_usuario_login','Nombre de usuario',NULL,NULL,NULL,1,NULL),(22,3,'Clave','clave_usuario',30,NULL,'\"obligatorio\":{\"Mensaje\":\"Debe ingresar su clave\",}',4,NULL,2,'clave_usuario_login','Clave Usuario',NULL,NULL,NULL,1,NULL),(23,4,NULL,'id_menu',NULL,NULL,NULL,1,NULL,NULL,'id_menu',NULL,NULL,NULL,NULL,1,NULL),(24,4,'Nombre Menu','nombre_menu',100,50,'\"obligatorio\":{\"mensaje\",\"Debe ingresar un nombre identificador del menu\"}',2,NULL,1,'nombre_menu',NULL,NULL,NULL,NULL,1,NULL),(25,5,NULL,'id_opcion_menu',NULL,NULL,NULL,1,NULL,NULL,'id_opcion_menu',NULL,NULL,NULL,NULL,1,NULL),(26,5,NULL,'id_menu',NULL,NULL,NULL,1,NULL,NULL,'id_menu',NULL,NULL,NULL,NULL,1,NULL),(27,5,'URL','url_opcion',100,50,'\"programa\":{\"mensaje\":\"formato de url invalido \"}',2,NULL,NULL,'url_opcion',NULL,NULL,NULL,NULL,1,NULL),(28,5,'Nombre de opcion','nombre_opcion',100,50,'\"obligatorio\":{\"mensaje\":\"El nombre de la opcion es obligatorio\"}',2,NULL,2,'nombre_opcion','Nombre a verse en el menu',NULL,NULL,'nombre que se vera en el menu',1,NULL),(29,5,'Padre','padre',NULL,NULL,NULL,7,'0=No Aplica;externo',NULL,'padre',NULL,NULL,NULL,NULL,1,NULL),(30,6,NULL,'id_objeto',NULL,NULL,NULL,1,NULL,NULL,'id_objeto',NULL,NULL,NULL,NULL,2,NULL),(31,6,'Objeto','objeto',50,50,'\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}',2,NULL,0,'objeto',NULL,NULL,NULL,NULL,2,NULL),(32,7,NULL,'id_objeto',NULL,NULL,NULL,1,NULL,0,'id_objeto',NULL,NULL,NULL,NULL,1,NULL),(33,7,'Nombre del Objeto','objeto',50,50,'\"obligatorio\":\"mensaje\":\"El campo es obligatorio\"}',2,NULL,1,'objeto',NULL,NULL,NULL,NULL,1,NULL),(34,7,'Componente','id_componente',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"debe seleccionar el componente al que pertenece el objeto\"}',7,'=Seleccione;Select id_componente,componente from s_componentes order by id_componente',3,'id_componente',NULL,NULL,NULL,NULL,1,NULL),(35,8,NULL,'id_componente',NULL,NULL,NULL,1,NULL,0,'id_componente',NULL,NULL,NULL,NULL,1,NULL),(36,8,'Componente','componente',50,50,'\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}',2,NULL,1,'componente',NULL,NULL,NULL,NULL,1,NULL),(37,9,NULL,'id_metodo',NULL,NULL,NULL,1,NULL,0,'id_metodo',NULL,NULL,NULL,NULL,1,NULL),(38,9,'Perfiles','id_perfil',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"Debe asignar algun perfil\"}',5,'Select id_perfil,perfil from s_perfiles',1,'perfil',NULL,NULL,NULL,NULL,1,NULL),(39,10,'Perfiles de Acceso','id_perfil',NULL,NULL,NULL,5,'select id_perfil,perfil from s_perfiles',1,'perfil',NULL,NULL,NULL,NULL,1,NULL),(40,11,'Perfil','id_perfil',NULL,NULL,NULL,5,'Select id_perfil,perfil from s_perfiles',2,'perfil',NULL,NULL,NULL,NULL,1,NULL),(41,12,'Perfil','id_perfil',NULL,NULL,NULL,5,'select id_perfil, perfil from s_perfiles',NULL,'perfil',NULL,NULL,NULL,NULL,1,NULL),(42,1,'Estructura','estructura',50,50,NULL,2,NULL,4,'estructura','Estructura',NULL,NULL,'Estructura de creacion del formulario',1,NULL),(43,2,'Clave','clave_evento',40,40,NULL,2,NULL,11,'clave_evento',NULL,NULL,NULL,NULL,1,NULL),(44,2,'Valor Evento','valor_evento',40,40,NULL,NULL,NULL,12,'valor_evento',NULL,NULL,NULL,NULL,1,NULL),(45,13,NULL,'id_perfil',NULL,NULL,NULL,2,NULL,NULL,'perfil',NULL,NULL,NULL,NULL,1,NULL),(46,13,NULL,'nombre_perfil',NULL,NULL,NULL,2,NULL,NULL,'nombre_perfil',NULL,NULL,NULL,NULL,1,NULL),(47,13,NULL,'fecha_creado',NULL,NULL,NULL,2,NULL,NULL,'fecha_creado',NULL,NULL,NULL,NULL,1,NULL),(48,13,NULL,'clave_perfil',NULL,NULL,NULL,2,NULL,NULL,'clave_perfil',NULL,NULL,NULL,NULL,1,NULL),(173,5,'Orden','orden',NULL,NULL,'\"numerico\":{\"mensaje\":\"El orden debe ser numerico\"}',2,NULL,NULL,'orden',NULL,NULL,NULL,NULL,1,NULL),(174,5,'Estatus','id_estatus',NULL,NULL,NULL,7,'select * from s_estatus where  id_estatus in(1,2)',NULL,'id_estatus',NULL,NULL,NULL,NULL,1,NULL),(175,5,'Icono','icono',NULL,NULL,NULL,NULL,NULL,NULL,'icono','clase css fuente o url de imagen',NULL,NULL,NULL,1,NULL),(176,5,'Selector del Icono','selector_icono',NULL,NULL,NULL,7,'1=Span;2=Imagen',NULL,'selector_icono',NULL,NULL,NULL,NULL,1,NULL),(177,14,NULL,'id_usuario',NULL,NULL,NULL,1,NULL,1,'id_usuario',NULL,NULL,NULL,NULL,1,NULL),(178,14,'Clave','clave_usuario',50,NULL,'\"obligatorio\":{\"mensaje\":\"Debe ingresar una clave para el usuario\"}',4,NULL,5,'clave_usuario','Clave',NULL,NULL,NULL,1,NULL),(179,14,'Nombres','nombres',NULL,NULL,NULL,2,NULL,2,'nombres','Nombres',NULL,NULL,NULL,1,NULL),(180,14,'Apellidos','apellidos',NULL,NULL,NULL,2,NULL,3,'apellidos','Apellidos',NULL,NULL,NULL,1,NULL),(181,14,'Nombre de Usuario','nombre_usuario',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"Debe ingresar un nombre de usuario o nickname\"}',NULL,NULL,4,'nombre_usuario','Nombre de Usuario',NULL,NULL,NULL,1,NULL),(182,14,'Correo El&eacute;ctronico','correo',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"Debe ingresar el correo del usuario\"},\"email\":{\"mensaje\":\"El formato del correo es incorrecto\"}',NULL,NULL,6,'correo',NULL,NULL,NULL,NULL,1,NULL),(183,14,'Estatus','id_estatus',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"Debe seleccionar el estatus\"},\"numerico\":{\"El id del estatus debe ser numerico\"}',7,'select id_estatus,estatus from s_estatus where id_estatus in (1,2) order by id_estatus',7,'id_estatus',NULL,NULL,NULL,NULL,1,NULL),(184,6,'Descripcion','descripcion',100,NULL,'\"alfanumerico\":{\"mensaje\":\"La descripci&oacute;n del objeto debe ser alfanumerica\"}',NULL,NULL,3,'descripcion',NULL,NULL,NULL,NULL,1,NULL),(185,5,'Perfiles Con Acceso','id_perfil',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"Debe Seleccionar Un Perfil\"}',5,'select id_perfil, perfil from s_perfiles',10,'id_perfil',NULL,NULL,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `s_jida_campos_f` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_jida_formularios`
--

DROP TABLE IF EXISTS `s_jida_formularios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_jida_formularios` (
  `id_form` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_f` varchar(80) NOT NULL,
  `query_f` text NOT NULL,
  `clave_primaria_f` varchar(45) DEFAULT NULL,
  `nombre_identificador` varchar(100) NOT NULL,
  `estructura` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_form`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_jida_formularios`
--

LOCK TABLES `s_jida_formularios` WRITE;
/*!40000 ALTER TABLE `s_jida_formularios` DISABLE KEYS */;
INSERT INTO `s_jida_formularios` VALUES (1,'formularios','select id_form,nombre_f,query_f,clave_primaria_f,estructura from s_jida_formularios','id_form','Formularios',NULL),(2,'Campos Formulario','select id_campo, id_form, label, name, maxlength, size,\n eventos, 1 clave_evento, 2 valor_evento, control,  opciones, orden, id_propiedad, placeholder,\n class, data_atributo, title, visibilidad from s_jida_campos_f','id_campo','CamposFormulario','2;3;1;2;1x2;2;1x3;3'),(3,'Login','select nombre_usuario,clave_usuario from s_usuarios',NULL,'Login',NULL),(4,'Procesar menus','select * from s_menus','id_menu','ProcesarMenus',NULL),(5,'Procesar opcion menu','select a.id_opcion_menu,id_menu,url_opcion,nombre_opcion,icono,orden, selector_icono,id_estatus, padre, id_perfil from s_opciones_menu a\n left join s_opciones_menu_perfiles b on (a.id_opcion_menu=b.id_opcion_menu)','a.id_opcion_menu','ProcesarOpcionMenu',NULL),(6,'sistema objetos','select id_objeto,objeto,descripcion from s_objetos','id_objeto','SistemaObjetos',NULL),(7,'Registro Objetos','select id_objeto,objeto,id_componente from s_objetos','id_objeto','RegistroObjetos',NULL),(8,'Componente','select id_componente,componente from s_componentes','id_componente','Componente',NULL),(9,'Perfiles a metodos','select id_metodo,id_perfil from s_metodos_perfiles','id_metodo','PerfilesAMetodos',NULL),(10,'Perfiles a objetos','select id_perfil from s_objetos_perfiles','id_objeto','PerfilesAObjetos',NULL),(11,'Perfiles a componentes','select id_perfil from s_componentes_perfiles','id_componente','PerfilesAComponentes',NULL),(12,'perfiles a usuario','select id_perfil from s_usuarios_perfiles','id_usuario','PerfilesAUsuario',NULL),(13,'Perfiles','select * from s_perfiles','id_perfil','Perfiles',NULL),(14,'registro Usuarios','select id_usuario,nombre_usuario,clave_usuario,nombres,apellidos,correo,id_estatus from s_usuarios','id_usuario','RegistroUsuarios',NULL),(15,'Descripcion Metodo','select descripcion from s_metodos','id_metodo','DescripcionMetodo','1'),(16,'Descripcion Objeto','select descripcion from s_objetos','id_objeto','DescripcionObjeto','1');
/*!40000 ALTER TABLE `s_jida_formularios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_menus`
--

DROP TABLE IF EXISTS `s_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_menus` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_menu` varchar(30) NOT NULL,
  `meta_data` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_menus`
--

LOCK TABLES `s_menus` WRITE;
/*!40000 ALTER TABLE `s_menus` DISABLE KEYS */;
INSERT INTO `s_menus` VALUES (1,'Principal',NULL),(2,'Administrador',NULL),(3,'topCliente',NULL);
/*!40000 ALTER TABLE `s_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_metodos`
--

DROP TABLE IF EXISTS `s_metodos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_metodos` (
  `id_metodo` int(11) NOT NULL AUTO_INCREMENT,
  `id_objeto` int(11) DEFAULT NULL,
  `metodo` varchar(150) DEFAULT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `loggin` int(11) DEFAULT '0',
  PRIMARY KEY (`id_metodo`),
  KEY `id_objeto` (`id_objeto`),
  CONSTRAINT `s_metodos_ibfk_1` FOREIGN KEY (`id_objeto`) REFERENCES `s_objetos` (`id_objeto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_metodos`
--

LOCK TABLES `s_metodos` WRITE;
/*!40000 ALTER TABLE `s_metodos` DISABLE KEYS */;
INSERT INTO `s_metodos` VALUES (38,22,'index',NULL,0),(39,23,'index',NULL,0);
/*!40000 ALTER TABLE `s_metodos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_metodos_perfiles`
--

DROP TABLE IF EXISTS `s_metodos_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_metodos_perfiles` (
  `id_metodo_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `id_metodo` int(11) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_metodo_perfil`),
  KEY `id_metodo` (`id_metodo`),
  KEY `id_perfil` (`id_perfil`),
  CONSTRAINT `fk_s_metodos` FOREIGN KEY (`id_metodo`) REFERENCES `s_metodos` (`id_metodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_perfiles` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_metodos_perfiles`
--

LOCK TABLES `s_metodos_perfiles` WRITE;
/*!40000 ALTER TABLE `s_metodos_perfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_metodos_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_objetos`
--

DROP TABLE IF EXISTS `s_objetos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_objetos` (
  `id_objeto` int(11) NOT NULL AUTO_INCREMENT,
  `id_componente` int(11) DEFAULT NULL,
  `objeto` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_objeto`),
  KEY `id_componente` (`id_componente`),
  CONSTRAINT `fk_s_objetos_s_componentes` FOREIGN KEY (`id_componente`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_objetos`
--

LOCK TABLES `s_objetos` WRITE;
/*!40000 ALTER TABLE `s_objetos` DISABLE KEYS */;
INSERT INTO `s_objetos` VALUES (22,2,'Jadmin',NULL),(23,3,'Admin',NULL);
/*!40000 ALTER TABLE `s_objetos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_objetos_media`
--

DROP TABLE IF EXISTS `s_objetos_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_objetos_media` (
  `id_objeto_media` int(11) NOT NULL AUTO_INCREMENT,
  `objeto_media` varchar(100) NOT NULL,
  `directorio` varchar(100) DEFAULT NULL,
  `tipo_media` int(11) DEFAULT NULL COMMENT '1= imagen; 2 = Video',
  `interno` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `leyenda` varchar(150) DEFAULT NULL,
  `alt` varchar(45) DEFAULT NULL,
  `meta_data` varchar(500) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_objeto_media`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_objetos_media`
--

LOCK TABLES `s_objetos_media` WRITE;
/*!40000 ALTER TABLE `s_objetos_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_objetos_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_objetos_perfiles`
--

DROP TABLE IF EXISTS `s_objetos_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_objetos_perfiles` (
  `id_objeto_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `id_objeto` int(11) NOT NULL,
  PRIMARY KEY (`id_objeto_perfil`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_objeto` (`id_objeto`),
  CONSTRAINT `s_objetos_perfiles_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_objetos_perfiles_ibfk_2` FOREIGN KEY (`id_objeto`) REFERENCES `s_objetos` (`id_objeto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_objetos_perfiles`
--

LOCK TABLES `s_objetos_perfiles` WRITE;
/*!40000 ALTER TABLE `s_objetos_perfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_objetos_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_opciones_menu`
--

DROP TABLE IF EXISTS `s_opciones_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_opciones_menu` (
  `id_opcion_menu` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id_opcion_menu`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `s_opciones_menu_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_opciones_menu`
--

LOCK TABLES `s_opciones_menu` WRITE;
/*!40000 ALTER TABLE `s_opciones_menu` DISABLE KEYS */;
INSERT INTO `s_opciones_menu` VALUES (1,1,'/jadmin/forms/','Formularios',0,1,'2014-02-13 13:01:11','2014-08-08 10:56:35','fa fa-check',2,1,1,NULL),(2,1,'/jadmin/menus/','Menus',0,0,'2014-02-13 13:01:11',NULL,'fa fa-bars',3,1,1,NULL),(3,1,NULL,'ACL',0,1,'2014-02-13 13:01:11',NULL,'fa fa-dashboard',1,1,1,NULL),(4,1,'/jadmin/objetos/','Objetos',3,0,'2014-02-13 13:01:11',NULL,NULL,NULL,1,NULL,NULL),(5,1,'/jadmin/componentes/','Componentes',3,0,'2014-02-13 13:01:11',NULL,NULL,NULL,1,NULL,NULL),(9,1,'/jadmin/perfiles/','Perfiles',3,0,NULL,NULL,NULL,NULL,1,NULL,NULL),(10,1,'/jadmin/users/cierresesion/','Cerrar Sesión',0,0,NULL,'2014-09-02 22:30:26','fa fa-power-off',10,1,1,NULL),(11,1,'/jadmin/users/','Usuarios',3,0,NULL,NULL,NULL,NULL,1,NULL,NULL),(27,1,'/jadmin/forms/jida-forms','Jida',1,0,'2014-08-04 05:31:21','2014-08-08 10:37:52',NULL,NULL,1,1,NULL),(28,1,'/jadmin/forms/filter/aplicacion','Aplicaci&oacute;n',1,0,'2014-08-04 05:54:06','2014-08-04 05:54:06','fa-plus-square-o',2,1,1,NULL),(29,1,'/algo-distinto/','1',1,0,'2014-08-08 10:57:10','2014-08-08 10:57:10',NULL,10,1,1,NULL);
/*!40000 ALTER TABLE `s_opciones_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_opciones_menu_perfiles`
--

DROP TABLE IF EXISTS `s_opciones_menu_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_opciones_menu_perfiles` (
  `id_opcion_menu_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `id_opcion_menu` int(11) DEFAULT NULL,
  `id_perfil` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_opcion_menu_perfil`),
  KEY `id_opcion` (`id_opcion_menu`),
  KEY `id_perfil` (`id_perfil`),
  CONSTRAINT `s_opciones_menu_perfiles_ibfk_1` FOREIGN KEY (`id_opcion_menu`) REFERENCES `s_opciones_menu` (`id_opcion_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_opciones_menu_perfiles_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_opciones_menu_perfiles`
--

LOCK TABLES `s_opciones_menu_perfiles` WRITE;
/*!40000 ALTER TABLE `s_opciones_menu_perfiles` DISABLE KEYS */;
INSERT INTO `s_opciones_menu_perfiles` VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,9,1),(7,10,1),(8,11,1),(9,27,1),(10,28,1),(11,29,1),(12,1,1),(13,2,1),(14,10,1),(15,3,1);
/*!40000 ALTER TABLE `s_opciones_menu_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_perfiles`
--

DROP TABLE IF EXISTS `s_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_perfiles` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `perfil` varchar(50) DEFAULT NULL,
  `fecha_creado` datetime DEFAULT NULL,
  `clave_perfil` varchar(100) NOT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_perfiles`
--

LOCK TABLES `s_perfiles` WRITE;
/*!40000 ALTER TABLE `s_perfiles` DISABLE KEYS */;
INSERT INTO `s_perfiles` VALUES (1,'Jida Administrador','2014-02-13 13:01:11','JidaAdministrador'),(2,'Administrador','2014-02-13 13:01:11','Administrador'),(3,'Usuario Publico','2014-02-13 13:01:11','UsuarioPublico'),(4,'Cliente','2014-03-16 07:51:20','Cliente');
/*!40000 ALTER TABLE `s_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_usuarios`
--

DROP TABLE IF EXISTS `s_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
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
  `img_perfil` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios`
--

LOCK TABLES `s_usuarios` WRITE;
/*!40000 ALTER TABLE `s_usuarios` DISABLE KEYS */;
INSERT INTO `s_usuarios` VALUES (1,'jadmin','3711be79067177199efb2589054a6894','2014-02-13 13:01:12',NULL,1,1,'2017-02-26 09:22:50','1',NULL,NULL,NULL,NULL,NULL,NULL),(2,'jeanpierre','e10adc3949ba59abbe56e057f20f883e',NULL,NULL,1,1,NULL,'1',NULL,NULL,'jeacontreras2009@gmail.com',NULL,NULL,NULL),(3,'felix','e10adc3949ba59abbe56e057f20f883e',NULL,NULL,1,1,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL),(4,'dayan','e10adc3949ba59abbe56e057f20f883e',NULL,NULL,1,1,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `s_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_usuarios_perfiles`
--

DROP TABLE IF EXISTS `s_usuarios_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_usuarios_perfiles` (
  `id_usuario_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario_perfil`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_perfil` (`id_perfil`),
  CONSTRAINT `s_usuarios_perfiles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `s_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_usuarios_perfiles_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios_perfiles`
--

LOCK TABLES `s_usuarios_perfiles` WRITE;
/*!40000 ALTER TABLE `s_usuarios_perfiles` DISABLE KEYS */;
INSERT INTO `s_usuarios_perfiles` VALUES (11,1,1),(12,1,2),(23,2,1),(24,2,2),(25,3,1),(26,3,2),(27,4,1),(28,4,2);
/*!40000 ALTER TABLE `s_usuarios_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vj_acceso_componentes`
--

DROP TABLE IF EXISTS `vj_acceso_componentes`;
/*!50001 DROP VIEW IF EXISTS `vj_acceso_componentes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vj_acceso_componentes` (
  `id_componente_perfil` tinyint NOT NULL,
  `id_perfil` tinyint NOT NULL,
  `clave_perfil` tinyint NOT NULL,
  `id_componente` tinyint NOT NULL,
  `componente` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vj_acceso_metodos`
--

DROP TABLE IF EXISTS `vj_acceso_metodos`;
/*!50001 DROP VIEW IF EXISTS `vj_acceso_metodos`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vj_acceso_metodos` (
  `id_metodo` tinyint NOT NULL,
  `id_objeto` tinyint NOT NULL,
  `objeto` tinyint NOT NULL,
  `metodo` tinyint NOT NULL,
  `loggin` tinyint NOT NULL,
  `id_perfil` tinyint NOT NULL,
  `clave_perfil` tinyint NOT NULL,
  `perfil` tinyint NOT NULL,
  `id_componente` tinyint NOT NULL,
  `componente` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vj_acceso_objetos`
--

DROP TABLE IF EXISTS `vj_acceso_objetos`;
/*!50001 DROP VIEW IF EXISTS `vj_acceso_objetos`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vj_acceso_objetos` (
  `id_objeto_perfil` tinyint NOT NULL,
  `id_perfil` tinyint NOT NULL,
  `clave_perfil` tinyint NOT NULL,
  `nombre_perfil` tinyint NOT NULL,
  `id_objeto` tinyint NOT NULL,
  `objeto` tinyint NOT NULL,
  `id_componente` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vj_perfiles_usuario`
--

DROP TABLE IF EXISTS `vj_perfiles_usuario`;
/*!50001 DROP VIEW IF EXISTS `vj_perfiles_usuario`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vj_perfiles_usuario` (
  `id_usuario_perfil` tinyint NOT NULL,
  `id_perfil` tinyint NOT NULL,
  `id_usuario` tinyint NOT NULL,
  `nombre_usuario` tinyint NOT NULL,
  `nombres` tinyint NOT NULL,
  `apellidos` tinyint NOT NULL,
  `clave_perfil` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vj_perfiles_usuarios`
--

DROP TABLE IF EXISTS `vj_perfiles_usuarios`;
/*!50001 DROP VIEW IF EXISTS `vj_perfiles_usuarios`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vj_perfiles_usuarios` (
  `id_usuario_perfil` tinyint NOT NULL,
  `id_perfil` tinyint NOT NULL,
  `id_usuario` tinyint NOT NULL,
  `nombre_usuario` tinyint NOT NULL,
  `nombres` tinyint NOT NULL,
  `apellidos` tinyint NOT NULL,
  `clave_perfil` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'laurafidalgo'
--

--
-- Final view structure for view `vj_acceso_componentes`
--

/*!50001 DROP TABLE IF EXISTS `vj_acceso_componentes`*/;
/*!50001 DROP VIEW IF EXISTS `vj_acceso_componentes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vj_acceso_componentes` AS select `a`.`id_componente_perfil` AS `id_componente_perfil`,`a`.`id_perfil` AS `id_perfil`,`b`.`clave_perfil` AS `clave_perfil`,`a`.`id_componente` AS `id_componente`,`c`.`componente` AS `componente` from ((`s_componentes_perfiles` `a` join `s_perfiles` `b` on((`a`.`id_perfil` = `b`.`id_perfil`))) join `s_componentes` `c` on((`c`.`id_componente` = `a`.`id_componente`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vj_acceso_metodos`
--

/*!50001 DROP TABLE IF EXISTS `vj_acceso_metodos`*/;
/*!50001 DROP VIEW IF EXISTS `vj_acceso_metodos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vj_acceso_metodos` AS select `a`.`id_metodo` AS `id_metodo`,`a`.`id_objeto` AS `id_objeto`,`d`.`objeto` AS `objeto`,`a`.`metodo` AS `metodo`,`a`.`loggin` AS `loggin`,`b`.`id_perfil` AS `id_perfil`,`c`.`clave_perfil` AS `clave_perfil`,`c`.`perfil` AS `perfil`,`e`.`id_componente` AS `id_componente`,`e`.`componente` AS `componente` from ((((`s_metodos` `a` left join `s_metodos_perfiles` `b` on((`a`.`id_metodo` = `b`.`id_metodo`))) left join `s_perfiles` `c` on((`c`.`id_perfil` = `b`.`id_perfil`))) join `s_objetos` `d` on((`a`.`id_objeto` = `d`.`id_objeto`))) join `s_componentes` `e` on((`d`.`id_componente` = `e`.`id_componente`))) where ((`b`.`id_perfil` is not null) or (`a`.`loggin` = 0)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vj_acceso_objetos`
--

/*!50001 DROP TABLE IF EXISTS `vj_acceso_objetos`*/;
/*!50001 DROP VIEW IF EXISTS `vj_acceso_objetos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vj_acceso_objetos` AS select `a`.`id_objeto_perfil` AS `id_objeto_perfil`,`a`.`id_perfil` AS `id_perfil`,`c`.`clave_perfil` AS `clave_perfil`,`c`.`perfil` AS `nombre_perfil`,`a`.`id_objeto` AS `id_objeto`,`b`.`objeto` AS `objeto`,`b`.`id_componente` AS `id_componente` from ((`s_objetos_perfiles` `a` join `s_objetos` `b` on((`b`.`id_objeto` = `a`.`id_objeto`))) join `s_perfiles` `c` on((`c`.`id_perfil` = `a`.`id_perfil`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vj_perfiles_usuario`
--

/*!50001 DROP TABLE IF EXISTS `vj_perfiles_usuario`*/;
/*!50001 DROP VIEW IF EXISTS `vj_perfiles_usuario`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vj_perfiles_usuario` AS select `a`.`id_usuario_perfil` AS `id_usuario_perfil`,`a`.`id_perfil` AS `id_perfil`,`a`.`id_usuario` AS `id_usuario`,`c`.`nombre_usuario` AS `nombre_usuario`,`c`.`nombres` AS `nombres`,`c`.`apellidos` AS `apellidos`,`b`.`clave_perfil` AS `clave_perfil` from ((`s_usuarios_perfiles` `a` join `s_perfiles` `b` on((`a`.`id_perfil` = `b`.`id_perfil`))) join `s_usuarios` `c` on((`a`.`id_usuario` = `c`.`id_usuario`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vj_perfiles_usuarios`
--

/*!50001 DROP TABLE IF EXISTS `vj_perfiles_usuarios`*/;
/*!50001 DROP VIEW IF EXISTS `vj_perfiles_usuarios`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vj_perfiles_usuarios` AS select `a`.`id_usuario_perfil` AS `id_usuario_perfil`,`a`.`id_perfil` AS `id_perfil`,`a`.`id_usuario` AS `id_usuario`,`c`.`nombre_usuario` AS `nombre_usuario`,`c`.`nombres` AS `nombres`,`c`.`apellidos` AS `apellidos`,`b`.`clave_perfil` AS `clave_perfil` from ((`s_usuarios_perfiles` `a` join `s_perfiles` `b` on((`a`.`id_perfil` = `b`.`id_perfil`))) join `s_usuarios` `c` on((`a`.`id_usuario` = `c`.`id_usuario`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-02-26 10:23:59
