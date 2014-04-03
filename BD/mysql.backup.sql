CREATE DATABASE  IF NOT EXISTS `framework` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `framework`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: localhost    Database: framework
-- ------------------------------------------------------
-- Server version	5.6.14

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
  PRIMARY KEY (`id_campo`),
  KEY `id_form` (`id_form`),
  CONSTRAINT `s_campos_f_ibfk_1` FOREIGN KEY (`id_form`) REFERENCES `s_formularios` (`id_form`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_campos_f`
--

LOCK TABLES `s_campos_f` WRITE;
/*!40000 ALTER TABLE `s_campos_f` DISABLE KEYS */;
INSERT INTO `s_campos_f` VALUES (1,1,'','id_form',NULL,NULL,'',1,'',0,'id_form','','','',NULL,1),(2,1,'Nombre Formulario','nombre_f',30,30,'',2,'',1,'nombre_f','','','',NULL,1),(3,1,'Query','query_f',NULL,NULL,'',3,'',2,'query_f','','','',NULL,1),(4,2,'','id_form',NULL,NULL,'',1,'',0,'id_form','','','',NULL,1),(5,2,'','id_campo',NULL,NULL,'',1,'',0,'id_campo','','','',NULL,1),(6,2,'Data','data_atributo',100,40,'',3,'',12,'data_atributo','','','data-jidacontrol=\"hola mundo\"',NULL,1),(7,2,'Clase','class',30,50,'\"programa\":{\"Mensaje\":\"Las clases CSS solo pueden contener caracteres alfanumericos, guión(-) y underscore(_)\"}',2,'',11,'class','','','',NULL,1),(8,2,'ID Propiedad','id_propiedad',30,30,'\"obligatorio\":{\"Mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}',2,'',3,'id_propiedad aefwefawef','','','',NULL,1),(9,2,'Orden','orden',20,20,'',2,'',9,'orden','','','',NULL,1),(10,2,'Opciones','opciones',NULL,NULL,'',3,'',7,'opciones','','','',NULL,1),(11,2,'Control','control',NULL,NULL,'',7,'=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion',4,'control','','','',NULL,1),(12,2,'Eventos','eventos',NULL,NULL,'',3,'',8,'eventos','','','',NULL,1),(13,2,'Size','size',20,20,'',2,'',6,'size','','','',NULL,1),(14,2,'Maxlength','maxlength',20,20,'',2,'',5,'maxlength','','','',NULL,1),(15,2,'Name','name',30,30,'\"obligatorio\":{\"Mensaje\":\"El campo debe llevar un nombre\"},\"programa\":{\"Mensaje\":\"El nombre no es valido\"}',2,'',2,'name','','','',NULL,1),(16,2,'Label','label',30,30,'\"obligatorio\":{\"Mensaje\":\"Debe indicar el label del campo\"},\"alfanumerico\":{\"Mensaje\":\"el Label solo debe poseer caracteres\"}',2,'',1,'label','','','',NULL,1),(17,2,'Placeholder','placeholder',100,30,'\"alfanumerico\":{\"Mensaje\":\"El placeholder solo puede contener letras y numeros\"}',2,'',10,'placeholder','hola mundo','','',NULL,1),(18,2,'Title','title',NULL,NULL,'',2,'',10,'title','','','',NULL,1),(19,2,'Visibilidad','visibilidad',NULL,NULL,'',7,'=Seleccione...;1=Normal;2=Readonly;3=Disabled',10,'visibilidad','','','',NULL,1),(20,3,'Nombre Usuario','nombre_usuario',30,NULL,'\"obligatorio\":{\"Mensaje\":\"Debe ingresar su nombre de Usuario\",},\"alfanumerico\":{\"Mensaje\":\"El nombre de usuario solo puede poseer caracteres alfanumericos\"}',2,NULL,NULL,'nombre_usuario','Nombre Usuario',NULL,NULL,'Ingrese su nombre de usuario',1),(21,3,'Clave','clave_usuario',30,NULL,'\"obligatorio\":{\"Mensaje\":\"Debe ingresar su nombre de Usuario\",}',4,NULL,2,'clave_usuario',NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `s_campos_f` ENABLE KEYS */;
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
  PRIMARY KEY (`id_form`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_formularios`
--

LOCK TABLES `s_formularios` WRITE;
/*!40000 ALTER TABLE `s_formularios` DISABLE KEYS */;
INSERT INTO `s_formularios` VALUES (1,'formularios','select id_form,nombre_f,query_f from s_formularios ','id_form','Formularios'),(2,'Campos Formulario','select * from s_campos_f ','id_campo','CamposFormulario'),(3,'Login','select nombre_usuario,clave_usuario from s_usuarios',NULL,'Login');
/*!40000 ALTER TABLE `s_formularios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_perfiles`
--

DROP TABLE IF EXISTS `s_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_perfiles` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_perfil` varchar(50) DEFAULT NULL,
  `fecha_creado` datetime DEFAULT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_perfiles`
--

LOCK TABLES `s_perfiles` WRITE;
/*!40000 ALTER TABLE `s_perfiles` DISABLE KEYS */;
INSERT INTO `s_perfiles` VALUES (1,'Jida Administrador','2014-01-05 09:44:47'),(2,'Administrador','2014-01-05 09:44:47'),(3,'Usuario','2014-01-05 09:44:47');
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
  `fecha_creado` datetime DEFAULT NULL,
  `fecha_modificado` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios`
--

LOCK TABLES `s_usuarios` WRITE;
/*!40000 ALTER TABLE `s_usuarios` DISABLE KEYS */;
INSERT INTO `s_usuarios` VALUES (1,'jadmin','jadmin','2014-01-05 09:44:47',NULL,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios_perfiles`
--

LOCK TABLES `s_usuarios_perfiles` WRITE;
/*!40000 ALTER TABLE `s_usuarios_perfiles` DISABLE KEYS */;
INSERT INTO `s_usuarios_perfiles` VALUES (1,1,1);
/*!40000 ALTER TABLE `s_usuarios_perfiles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-05 10:10:14
