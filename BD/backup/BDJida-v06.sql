-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: marbella
-- ------------------------------------------------------
-- Server version	5.6.21

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
-- Table structure for table `s_clasificacion_posts`
--

DROP TABLE IF EXISTS `s_clasificacion_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_clasificacion_posts` (
  `id_clasificacion_post` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_clasificacion` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_clasificacion_post`),
  KEY `fk_t_posts_r_clasificacion_post_idx` (`id_post`),
  KEY `fk_s_clasificacion_post_r_clasificacion_post_idx` (`id_clasificacion`),
  CONSTRAINT `fk_s_clasificaciones_s_clasificacion_post` FOREIGN KEY (`id_clasificacion`) REFERENCES `s_clasificaciones` (`id_clasificacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_t_posts_r_clasificacion_post` FOREIGN KEY (`id_post`) REFERENCES `s_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_clasificacion_posts`
--

LOCK TABLES `s_clasificacion_posts` WRITE;
/*!40000 ALTER TABLE `s_clasificacion_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_clasificacion_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_clasificaciones`
--

DROP TABLE IF EXISTS `s_clasificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_clasificaciones` (
  `id_clasificacion` int(11) NOT NULL AUTO_INCREMENT,
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
  `id_idioma` int(11) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_clasificacion`),
  KEY `fk_s_estatus_idx` (`id_estatus`),
  KEY `fk_s_idiomas_s_clasificacion_post_idx` (`id_idioma`),
  KEY `fk_s_clasificacion_post_texto_original_idx` (`texto_original`),
  CONSTRAINT `fk_s_estatus` FOREIGN KEY (`id_estatus`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_idiomas_s_clasificacion_post` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_clasificaciones`
--

LOCK TABLES `s_clasificaciones` WRITE;
/*!40000 ALTER TABLE `s_clasificaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_clasificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_comentarios_posts`
--

DROP TABLE IF EXISTS `s_comentarios_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_comentarios_posts` (
  `id_comentario_post` int(11) NOT NULL AUTO_INCREMENT,
  `comentario_post` text,
  `nombres` varchar(25) DEFAULT NULL,
  `apellidos` varchar(35) DEFAULT NULL,
  `correo` varchar(35) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_Creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_comentario_post`),
  KEY `fk_s_usuarios_idx` (`id_usuario`),
  KEY `fk_t_comentarios_t_post_idx` (`id_post`),
  CONSTRAINT `fk_s_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `s_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_t_comentarios_t_post` FOREIGN KEY (`id_post`) REFERENCES `s_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_comentarios_posts`
--

LOCK TABLES `s_comentarios_posts` WRITE;
/*!40000 ALTER TABLE `s_comentarios_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_comentarios_posts` ENABLE KEYS */;
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
  `identificador` varchar(100) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modifcador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_componente`),
  KEY `fk_s_idiomas_s_componentes_idx` (`id_idioma`),
  KEY `fk_texto_original_s_componentes_idx` (`texto_original`),
  CONSTRAINT `fk_s_idiomas_s_componentes` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_texto_original_s_componentes` FOREIGN KEY (`texto_original`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_componentes`
--

LOCK TABLES `s_componentes` WRITE;
/*!40000 ALTER TABLE `s_componentes` DISABLE KEYS */;
INSERT INTO `s_componentes` VALUES (1,'principal',NULL,'principal',NULL,NULL,NULL,NULL,NULL,NULL),(2,'jadmin',NULL,'jadmin',NULL,NULL,NULL,NULL,NULL,NULL),(3,'admin',NULL,'admin',NULL,NULL,NULL,NULL,NULL,NULL);
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
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_componente_perfil`),
  KEY `id_perfil` (`id_perfil`),
  KEY `id_componente` (`id_componente`),
  CONSTRAINT `s_componentes_perfiles_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_componentes_perfiles_ibfk_2` FOREIGN KEY (`id_componente`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_componentes_perfiles`
--

LOCK TABLES `s_componentes_perfiles` WRITE;
/*!40000 ALTER TABLE `s_componentes_perfiles` DISABLE KEYS */;
INSERT INTO `s_componentes_perfiles` VALUES (1,1,2,NULL,NULL,NULL,NULL),(2,1,3,NULL,NULL,NULL,NULL),(3,2,3,NULL,NULL,NULL,NULL),(4,1,1,NULL,NULL,NULL,NULL),(5,2,1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `s_componentes_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_elementos`
--

DROP TABLE IF EXISTS `s_elementos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_elementos` (
  `id_elemento` int(11) NOT NULL AUTO_INCREMENT,
  `elemento` varchar(50) DEFAULT NULL,
  `data` text,
  `area` varchar(80) DEFAULT NULL,
  `identificador` varchar(100) DEFAULT NULL,
  `id_idioma` int(11) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_elemento`),
  KEY `fk_s_idiomas_s_elementos_idx` (`id_idioma`),
  KEY `fk_s_elementos_texto_original_idx` (`texto_original`),
  CONSTRAINT `fk_s_elementos_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_elementos` (`id_elemento`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_idiomas_s_elementos` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `identificador` varchar(80) DEFAULT NULL,
  `id_idioma` int(11) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_estatus`),
  KEY `fk_s_idiomas_s_estatus_idx` (`id_idioma`),
  KEY `fk_s_idiomas_texto_originas_idx` (`texto_original`),
  CONSTRAINT `fk_s_estatus_texto_originas` FOREIGN KEY (`texto_original`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_idiomas_s_estatus` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_estatus`
--

LOCK TABLES `s_estatus` WRITE;
/*!40000 ALTER TABLE `s_estatus` DISABLE KEYS */;
INSERT INTO `s_estatus` VALUES (1,'Activo','activo',NULL,NULL,NULL,NULL,NULL,NULL),(2,'Inactivo','inactivo',NULL,NULL,NULL,NULL,NULL,NULL),(3,'Eliminado','eliminado',NULL,NULL,NULL,NULL,NULL,NULL),(4,'Data Incompleta','data_incompleta',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `s_estatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_estatus_posts`
--

DROP TABLE IF EXISTS `s_estatus_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_estatus_posts` (
  `id_estatus_post` int(11) NOT NULL AUTO_INCREMENT,
  `estatus_post` varchar(80) DEFAULT NULL,
  `identificador` varchar(80) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_estatus_post`),
  KEY `fk_s_idiomas_idx` (`id_idioma`),
  KEY `fk_texto_original_idx` (`texto_original`),
  KEY `sk_s_idiomas_s_estatus_post_idx` (`id_idioma`),
  CONSTRAINT `fk_s_estatus_posts_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_estatus_posts` (`id_estatus_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sk_s_idiomas_s_estatus_post` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_estatus_posts`
--

LOCK TABLES `s_estatus_posts` WRITE;
/*!40000 ALTER TABLE `s_estatus_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_estatus_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_idiomas`
--

DROP TABLE IF EXISTS `s_idiomas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_idiomas` (
  `id_idioma` int(11) NOT NULL AUTO_INCREMENT,
  `idioma` varchar(20) DEFAULT NULL,
  `por_defecto` tinyint(4) DEFAULT NULL,
  `identificador` varchar(30) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_idioma`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_idiomas`
--

LOCK TABLES `s_idiomas` WRITE;
/*!40000 ALTER TABLE `s_idiomas` DISABLE KEYS */;
INSERT INTO `s_idiomas` VALUES (1,'Español',1,'español',NULL,NULL,NULL,NULL),(2,'Ingles',NULL,'ingles',NULL,NULL,NULL,NULL),(3,'Portugues',NULL,'portugues',NULL,NULL,NULL,NULL),(4,'Italiano',NULL,'italiano',NULL,NULL,NULL,NULL),(5,'Frances',NULL,'frances',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `s_idiomas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_menus`
--

DROP TABLE IF EXISTS `s_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_menus` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(50) NOT NULL,
  `meta_data` varchar(200) DEFAULT NULL,
  `identificador` varchar(60) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modifcador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_menu`),
  KEY `fk_s_idiomas_s_menus_idx` (`id_idioma`),
  KEY `fk_s_menus_texto_original_idx` (`texto_original`),
  CONSTRAINT `fk_s_idiomas_s_menus` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_menus_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_menus`
--

LOCK TABLES `s_menus` WRITE;
/*!40000 ALTER TABLE `s_menus` DISABLE KEYS */;
INSERT INTO `s_menus` VALUES (1,'Principal',NULL,'principal',NULL,NULL,NULL,NULL,NULL,NULL),(2,'Administrador',NULL,'administrador',NULL,NULL,NULL,NULL,NULL,NULL),(3,'topCliente',NULL,'topcliente',NULL,NULL,NULL,NULL,NULL,NULL);
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
  `descripcion` varchar(250) DEFAULT NULL,
  `identificador` varchar(160) DEFAULT NULL,
  `loggin` int(11) DEFAULT '0',
  `id_idioma` int(11) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_metodo`),
  KEY `id_objeto` (`id_objeto`),
  KEY `fk_s_idiomas_s_metodos_idx` (`id_idioma`),
  KEY `sk_s_metodos_texto_original_idx` (`texto_original`),
  CONSTRAINT `fk_s_idiomas_s_metodos` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_metodos_ibfk_1` FOREIGN KEY (`id_objeto`) REFERENCES `s_objetos` (`id_objeto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sk_s_metodos_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_metodos` (`id_metodo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_metodos`
--

LOCK TABLES `s_metodos` WRITE;
/*!40000 ALTER TABLE `s_metodos` DISABLE KEYS */;
INSERT INTO `s_metodos` VALUES (38,22,'index',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL),(39,23,'index',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL);
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
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
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
  `identificador` varchar(120) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_objeto`),
  KEY `id_componente` (`id_componente`),
  KEY `fk_s_idiomas_s_objetos_idx` (`id_idioma`),
  KEY `fk_s_objetos_texto_original_idx` (`texto_original`),
  CONSTRAINT `fk_s_idiomas_s_objetos` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_objetos_s_componentes` FOREIGN KEY (`id_componente`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_objetos_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_objetos` (`id_objeto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_objetos`
--

LOCK TABLES `s_objetos` WRITE;
/*!40000 ALTER TABLE `s_objetos` DISABLE KEYS */;
INSERT INTO `s_objetos` VALUES (22,2,'Jadmin',NULL,'jadmin',NULL,NULL,NULL,NULL,NULL,NULL),(23,3,'Admin',NULL,'admin',NULL,NULL,NULL,NULL,NULL,NULL);
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
  `id_idioma` int(11) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_objeto_media`),
  KEY `fk_s_idiomas_s_objetos_media_idx` (`id_idioma`),
  KEY `fk_s_objetos_media_texto_original_idx` (`texto_original`),
  CONSTRAINT `fk_s_idiomas_s_objetos_media` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_objetos_media_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_objetos_media` (`id_objeto_media`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_objetos_media`
--

LOCK TABLES `s_objetos_media` WRITE;
/*!40000 ALTER TABLE `s_objetos_media` DISABLE KEYS */;
INSERT INTO `s_objetos_media` VALUES (1,'c8ee254a8fe7b483e3e41c9399a614bc260098.jpeg','/cargas/2017/01/',0,1,NULL,NULL,NULL,'{\"img\":\"c8ee254a8fe7b483e3e41c9399a614bc260098.jpeg\",\"sm\":\"c8ee254a8fe7b483e3e41c9399a614bc260098-sm.jpeg\",\"min\":\"c8ee254a8fe7b483e3e41c9399a614bc260098-min.jpeg\",\"md\":\"c8ee254a8fe7b483e3e41c9399a614bc260098-md.jpeg\",\"lg\":\"c8ee254a8fe7b483e3e41c9399a614bc260098-lg.jpeg\"}',NULL,NULL,3,3,'2017-01-12 17:40:42','2017-01-12 17:40:42'),(2,'b52d4f0dbade433a5bc5c9227202a6fa330575.jpeg','/cargas/2017/01/',0,1,NULL,NULL,NULL,'{\"img\":\"b52d4f0dbade433a5bc5c9227202a6fa330575.jpeg\",\"sm\":\"b52d4f0dbade433a5bc5c9227202a6fa330575-sm.jpeg\",\"min\":\"b52d4f0dbade433a5bc5c9227202a6fa330575-min.jpeg\",\"md\":\"b52d4f0dbade433a5bc5c9227202a6fa330575-md.jpeg\",\"lg\":\"b52d4f0dbade433a5bc5c9227202a6fa330575-lg.jpeg\"}',NULL,NULL,3,3,'2017-01-12 17:59:39','2017-01-12 17:59:39');
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
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
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
  `id_idioma` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_opcion_menu`),
  KEY `id_menu` (`id_menu`),
  KEY `fk_s_idiomas_s_opciones_menu_idx` (`id_idioma`),
  KEY `fk_s_opciones_menu_texto_original_idx` (`texto_original`),
  KEY `fk_s_estatus_s_opciones_menu_idx` (`id_estatus`),
  KEY `fk_s_metodos_s_opciones_menu_idx` (`id_metodo`),
  CONSTRAINT `fk_s_estatus_s_opciones_menu` FOREIGN KEY (`id_estatus`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_idiomas_s_opciones_menu` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_metodos_s_opciones_menu` FOREIGN KEY (`id_metodo`) REFERENCES `s_metodos` (`id_metodo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_opciones_menu_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_opciones_menu` (`id_opcion_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_opciones_menu_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_opciones_menu`
--

LOCK TABLES `s_opciones_menu` WRITE;
/*!40000 ALTER TABLE `s_opciones_menu` DISABLE KEYS */;
INSERT INTO `s_opciones_menu` VALUES (3,'ACL',NULL,NULL,0,1,'fa fa-dashboard',1,1,1,1,NULL,NULL,NULL,'2014-02-13 13:01:11',NULL,NULL,NULL),(4,'Objetos','/jadmin/objetos/',NULL,3,0,NULL,NULL,1,1,NULL,NULL,NULL,NULL,'2014-02-13 13:01:11',NULL,NULL,NULL),(5,'Componentes','/jadmin/componentes/',NULL,3,0,NULL,NULL,1,1,NULL,NULL,NULL,NULL,'2014-02-13 13:01:11',NULL,NULL,NULL),(9,'Perfiles','/jadmin/perfiles/',NULL,3,0,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,'Cerrar Sesión','/jadmin/users/cierresesion/',NULL,0,0,'fa fa-power-off',100,1,1,1,NULL,NULL,NULL,NULL,'2014-09-02 22:30:26',NULL,3),(11,'Usuarios','/jadmin/users/',NULL,3,0,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,'Jida','/jadmin/forms/jida-forms',NULL,1,0,NULL,NULL,1,1,1,NULL,NULL,NULL,'2014-08-04 05:31:21','2014-08-08 10:37:52',NULL,NULL),(28,'Aplicaci&oacute;n','/jadmin/forms/filter/aplicacion',NULL,1,0,'fa-plus-square-o',2,1,1,1,NULL,NULL,NULL,'2014-08-04 05:54:06','2014-08-04 05:54:06',NULL,NULL),(29,'1','/algo-distinto/',NULL,1,0,NULL,10,1,1,1,NULL,NULL,NULL,'2014-08-08 10:57:10','2014-08-08 10:57:10',NULL,NULL),(30,'Formularios','/jadmin/formularios',NULL,0,NULL,'fa fa-edit',10,1,1,1,NULL,NULL,NULL,'2017-06-18 12:33:57','2017-06-18 12:33:57',1,1),(33,'Menues','/jadmin/menus',NULL,0,NULL,'fa fa-bars',20,1,1,1,NULL,NULL,NULL,'2017-06-18 16:34:33','2017-06-18 16:34:33',1,1),(34,'Líneas','/jadmin/lineas',NULL,0,NULL,'fa fa-briefcase',2,1,1,1,NULL,NULL,NULL,'2017-06-18 16:35:57','2017-06-18 16:35:57',1,3),(36,'Dashboard','/jadmin/dashboard',NULL,0,NULL,'fa fa-dashboard',1,1,1,1,NULL,NULL,NULL,'2017-06-20 19:56:31','2017-06-20 19:56:31',3,3);
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
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_opcion_menu_perfil`),
  KEY `id_opcion` (`id_opcion_menu`),
  KEY `id_perfil` (`id_perfil`),
  CONSTRAINT `s_opciones_menu_perfiles_ibfk_1` FOREIGN KEY (`id_opcion_menu`) REFERENCES `s_opciones_menu` (`id_opcion_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_opciones_menu_perfiles_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_opciones_menu_perfiles`
--

LOCK TABLES `s_opciones_menu_perfiles` WRITE;
/*!40000 ALTER TABLE `s_opciones_menu_perfiles` DISABLE KEYS */;
INSERT INTO `s_opciones_menu_perfiles` VALUES (3,3,1,NULL,NULL,NULL,NULL),(4,4,1,NULL,NULL,NULL,NULL),(5,5,1,NULL,NULL,NULL,NULL),(6,9,1,NULL,NULL,NULL,NULL),(8,11,1,NULL,NULL,NULL,NULL),(9,27,1,NULL,NULL,NULL,NULL),(10,28,1,NULL,NULL,NULL,NULL),(11,29,1,NULL,NULL,NULL,NULL),(15,3,1,NULL,NULL,NULL,NULL),(16,30,1,NULL,NULL,NULL,NULL),(32,36,1,NULL,NULL,NULL,NULL),(33,36,2,NULL,NULL,NULL,NULL),(34,34,1,NULL,NULL,NULL,NULL),(35,34,2,NULL,NULL,NULL,NULL),(36,33,1,NULL,NULL,NULL,NULL),(37,10,1,NULL,NULL,NULL,NULL),(38,10,2,NULL,NULL,NULL,NULL);
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
  `identificador` varchar(60) DEFAULT NULL,
  `id_idioma` int(11) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_perfil`),
  KEY `fk_s_idiomas_s_perfiles_idx` (`id_idioma`),
  KEY `fk_s_perfiles_texto_original_idx` (`texto_original`),
  CONSTRAINT `fk_s_idiomas_s_perfiles` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_perfiles_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_perfiles`
--

LOCK TABLES `s_perfiles` WRITE;
/*!40000 ALTER TABLE `s_perfiles` DISABLE KEYS */;
INSERT INTO `s_perfiles` VALUES (1,'Jida Administrador','2014-02-13 13:01:11','JidaAdministrador','jidaadministrador',NULL,NULL,NULL,NULL,NULL,NULL),(2,'Administrador','2014-02-13 13:01:11','Administrador','administrador',NULL,NULL,NULL,NULL,NULL,NULL),(3,'Usuario Publico','2014-02-13 13:01:11','UsuarioPublico','usuariopublico',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `s_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_posts`
--

DROP TABLE IF EXISTS `s_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_posts` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
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
  `id_idioma` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_post`),
  KEY `id_seccion_idx` (`id_seccion`),
  KEY `id_estatus_post_idx` (`id_estatus_post`),
  KEY `id_idioma_idx` (`id_idioma`),
  KEY `fk_texto_original_idx` (`texto_original`),
  KEY `s_post_s_objetos_media_idx` (`id_media_principal`),
  CONSTRAINT `fk_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_estatus_post` FOREIGN KEY (`id_estatus_post`) REFERENCES `s_estatus_posts` (`id_estatus_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_idioma` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_seccion` FOREIGN KEY (`id_seccion`) REFERENCES `s_clasificaciones` (`id_clasificacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_post_s_objetos_media` FOREIGN KEY (`id_media_principal`) REFERENCES `s_objetos_media` (`id_objeto_media`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_posts`
--

LOCK TABLES `s_posts` WRITE;
/*!40000 ALTER TABLE `s_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_posts` ENABLE KEYS */;
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
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_s_usuarios_s_estatus_idx` (`id_estatus`),
  CONSTRAINT `fk_s_usuarios_s_estatus` FOREIGN KEY (`id_estatus`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios`
--

LOCK TABLES `s_usuarios` WRITE;
/*!40000 ALTER TABLE `s_usuarios` DISABLE KEYS */;
INSERT INTO `s_usuarios` VALUES (1,'jadmin','3711be79067177199efb2589054a6894',NULL,1,1,'2017-06-18 10:33:25','1',NULL,NULL,NULL,NULL,NULL,NULL,'2014-02-13 13:01:12',NULL,NULL,NULL),(2,'jeanpierre','e10adc3949ba59abbe56e057f20f883e',NULL,1,1,NULL,'1',NULL,NULL,'jeacontreras2009@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'felix','e10adc3949ba59abbe56e057f20f883e',NULL,1,1,'2017-06-20 20:26:13','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'dayan','e10adc3949ba59abbe56e057f20f883e',NULL,1,1,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'admin','e10adc3949ba59abbe56e057f20f883e',NULL,1,1,'2017-06-20 20:27:06','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario_perfil`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_perfil` (`id_perfil`),
  CONSTRAINT `s_usuarios_perfiles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `s_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `s_usuarios_perfiles_ibfk_2` FOREIGN KEY (`id_perfil`) REFERENCES `s_perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios_perfiles`
--

LOCK TABLES `s_usuarios_perfiles` WRITE;
/*!40000 ALTER TABLE `s_usuarios_perfiles` DISABLE KEYS */;
INSERT INTO `s_usuarios_perfiles` VALUES (1,1,1,NULL,NULL,NULL,NULL),(2,1,2,NULL,NULL,NULL,NULL),(3,2,1,NULL,NULL,NULL,NULL),(4,2,2,NULL,NULL,NULL,NULL),(5,3,1,NULL,NULL,NULL,NULL),(6,3,2,NULL,NULL,NULL,NULL),(7,4,1,NULL,NULL,NULL,NULL),(8,4,2,NULL,NULL,NULL,NULL),(9,5,2,NULL,NULL,NULL,NULL);
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

-- Dump completed on 2017-06-22 12:15:23
