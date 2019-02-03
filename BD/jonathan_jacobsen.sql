-- MySQL dump 10.13  Distrib 8.0.14, for Win64 (x86_64)
--
-- Host: localhost    Database: jacobsen
-- ------------------------------------------------------
-- Server version	8.0.13

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `m_categorias`
--

DROP TABLE IF EXISTS `m_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `m_categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `identificador` text,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_categorias`
--

LOCK TABLES `m_categorias` WRITE;
/*!40000 ALTER TABLE `m_categorias` DISABLE KEYS */;
INSERT INTO `m_categorias` VALUES (5,'Bodas',NULL,NULL,'2019-01-21 22:12:16','2019-01-21 22:12:16',0,0);
/*!40000 ALTER TABLE `m_categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `m_proyectos`
--

DROP TABLE IF EXISTS `m_proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `m_proyectos` (
  `id_proyecto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `identificador` text,
  `id_categoria` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_proyecto`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_proyectos`
--

LOCK TABLES `m_proyectos` WRITE;
/*!40000 ALTER TABLE `m_proyectos` DISABLE KEYS */;
INSERT INTO `m_proyectos` VALUES (4,'Album 1',NULL,'album-1',5,'2019-01-21 22:12:28','2019-01-21 22:12:28',0,0),(5,'Album 2',NULL,'album-2',5,'2019-01-21 22:13:32','2019-01-21 22:13:32',0,0);
/*!40000 ALTER TABLE `m_proyectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_clasificacion_posts`
--

DROP TABLE IF EXISTS `s_clasificacion_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
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
 SET character_set_client = utf8mb4 ;
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
  `id_idioma` varchar(5) DEFAULT NULL,
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
  CONSTRAINT `fk_s_idiomas_s_clasificaciones` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `texto_original_s_clasificaciones` FOREIGN KEY (`texto_original`) REFERENCES `s_clasificaciones` (`id_clasificacion`) ON DELETE CASCADE ON UPDATE CASCADE
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
 SET character_set_client = utf8mb4 ;
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
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_componentes` (
  `id_componente` int(11) NOT NULL AUTO_INCREMENT,
  `componente` varchar(100) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `identificador` varchar(100) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modifcador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_componente`),
  KEY `fk_s_idiomas_s_componentes_idx` (`id_idioma`),
  KEY `fk_texto_original_s_componentes_idx` (`texto_original`),
  CONSTRAINT `fk_texto_original_s_componentes` FOREIGN KEY (`texto_original`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fks_idiomas_s_componentes` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_componentes`
--

LOCK TABLES `s_componentes` WRITE;
/*!40000 ALTER TABLE `s_componentes` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_componentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_componentes_perfiles`
--

DROP TABLE IF EXISTS `s_componentes_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_componentes_perfiles`
--

LOCK TABLES `s_componentes_perfiles` WRITE;
/*!40000 ALTER TABLE `s_componentes_perfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_componentes_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_elementos`
--

DROP TABLE IF EXISTS `s_elementos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_elementos` (
  `id_elemento` int(11) NOT NULL AUTO_INCREMENT,
  `elemento` varchar(50) DEFAULT NULL,
  `data` text,
  `area` varchar(80) DEFAULT NULL,
  `identificador` varchar(100) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
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
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_estatus` (
  `id_estatus` int(11) NOT NULL AUTO_INCREMENT,
  `estatus` varchar(40) DEFAULT NULL,
  `identificador` varchar(80) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_estatus`
--

LOCK TABLES `s_estatus` WRITE;
/*!40000 ALTER TABLE `s_estatus` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_estatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_estatus_posts`
--

DROP TABLE IF EXISTS `s_estatus_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_estatus_posts` (
  `id_estatus_post` int(11) NOT NULL AUTO_INCREMENT,
  `estatus_post` varchar(80) DEFAULT NULL,
  `identificador` varchar(80) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_estatus_post`),
  KEY `fk_s_idiomas_idx` (`id_idioma`),
  KEY `fk_texto_original_idx` (`texto_original`),
  KEY `sk_s_idiomas_s_estatus_post_idx` (`id_idioma`),
  CONSTRAINT `fk_s_estatus_posts_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_estatus_posts` (`id_estatus_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_idiomas_s_estatus_posts` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE
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
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_idiomas` (
  `id_idioma` varchar(5) NOT NULL,
  `idioma` varchar(20) DEFAULT NULL,
  `por_defecto` tinyint(4) DEFAULT NULL,
  `identificador` varchar(30) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_idiomas`
--

LOCK TABLES `s_idiomas` WRITE;
/*!40000 ALTER TABLE `s_idiomas` DISABLE KEYS */;
INSERT INTO `s_idiomas` VALUES ('esp','Espanol',1,'esp',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `s_idiomas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_menus`
--

DROP TABLE IF EXISTS `s_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_menus` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(50) NOT NULL,
  `meta_data` varchar(200) DEFAULT NULL,
  `identificador` varchar(60) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_menu`),
  KEY `fk_s_idiomas_s_menus_idx` (`id_idioma`),
  KEY `fk_s_menus_texto_original_idx` (`texto_original`),
  CONSTRAINT `fk_s_idiomas__s_menus` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_menus_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_menus`
--

LOCK TABLES `s_menus` WRITE;
/*!40000 ALTER TABLE `s_menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_metodos`
--

DROP TABLE IF EXISTS `s_metodos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_metodos` (
  `id_metodo` int(11) NOT NULL AUTO_INCREMENT,
  `id_objeto` int(11) DEFAULT NULL,
  `metodo` varchar(150) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `identificador` varchar(160) DEFAULT NULL,
  `loggin` int(11) DEFAULT '0',
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_metodo`),
  KEY `id_objeto` (`id_objeto`),
  CONSTRAINT `s_metodos_ibfk_1` FOREIGN KEY (`id_objeto`) REFERENCES `s_objetos` (`id_objeto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_metodos`
--

LOCK TABLES `s_metodos` WRITE;
/*!40000 ALTER TABLE `s_metodos` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_metodos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_metodos_perfiles`
--

DROP TABLE IF EXISTS `s_metodos_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
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
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_objetos` (
  `id_objeto` int(11) NOT NULL AUTO_INCREMENT,
  `id_componente` int(11) DEFAULT NULL,
  `objeto` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `identificador` varchar(120) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_objeto`),
  KEY `id_componente` (`id_componente`),
  CONSTRAINT `fk_s_objetos_s_componentes` FOREIGN KEY (`id_componente`) REFERENCES `s_componentes` (`id_componente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_objetos`
--

LOCK TABLES `s_objetos` WRITE;
/*!40000 ALTER TABLE `s_objetos` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_objetos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_objetos_media`
--

DROP TABLE IF EXISTS `s_objetos_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_objetos_media` (
  `id_objeto_media` int(11) NOT NULL AUTO_INCREMENT,
  `objeto_media` varchar(100) NOT NULL,
  `directorio` varchar(100) DEFAULT NULL,
  `tipo_media` varchar(40) DEFAULT NULL COMMENT '1= imagen; 2 = Video',
  `interno` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `leyenda` varchar(150) DEFAULT NULL,
  `modulo` varchar(60) DEFAULT NULL,
  `meta_data` varchar(500) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_objeto_media`),
  KEY `fk_s_idiomas_s_objetos_media_idx` (`id_idioma`),
  KEY `fk_s_objetos_media_texto_original_idx` (`texto_original`),
  KEY `i_modulo_objeto` (`modulo`),
  CONSTRAINT `fk_s_idiomas_s_objetos_media` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_objetos_media_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_objetos_media` (`id_objeto_media`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
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
 SET character_set_client = utf8mb4 ;
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
 SET character_set_client = utf8mb4 ;
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
  `id_idioma` varchar(5) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_opciones_menu`
--

LOCK TABLES `s_opciones_menu` WRITE;
/*!40000 ALTER TABLE `s_opciones_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_opciones_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_opciones_menu_perfiles`
--

DROP TABLE IF EXISTS `s_opciones_menu_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_opciones_menu_perfiles`
--

LOCK TABLES `s_opciones_menu_perfiles` WRITE;
/*!40000 ALTER TABLE `s_opciones_menu_perfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_opciones_menu_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_perfiles`
--

DROP TABLE IF EXISTS `s_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_perfiles` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `perfil` varchar(50) DEFAULT NULL,
  `fecha_creado` datetime DEFAULT NULL,
  `identificador` varchar(60) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_perfiles`
--

LOCK TABLES `s_perfiles` WRITE;
/*!40000 ALTER TABLE `s_perfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_posts`
--

DROP TABLE IF EXISTS `s_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
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
  `id_idioma` varchar(5) DEFAULT NULL,
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
  CONSTRAINT `fk_s_idiomas_s_posts` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `s_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_estatus_post` FOREIGN KEY (`id_estatus_post`) REFERENCES `s_estatus_posts` (`id_estatus_post`) ON DELETE CASCADE ON UPDATE CASCADE,
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
 SET character_set_client = utf8mb4 ;
CREATE TABLE `s_usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
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
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_s_usuarios_s_estatus_idx` (`id_estatus`),
  CONSTRAINT `fk_s_usuarios_s_estatus` FOREIGN KEY (`id_estatus`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios`
--

LOCK TABLES `s_usuarios` WRITE;
/*!40000 ALTER TABLE `s_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_usuarios_perfiles`
--

DROP TABLE IF EXISTS `s_usuarios_perfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios_perfiles`
--

LOCK TABLES `s_usuarios_perfiles` WRITE;
/*!40000 ALTER TABLE `s_usuarios_perfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `s_usuarios_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_media_proyectos`
--

DROP TABLE IF EXISTS `t_media_proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `t_media_proyectos` (
  `id_media_proyecto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `directorio` varchar(100) DEFAULT NULL,
  `tipo_media` varchar(40) DEFAULT NULL COMMENT '1= imagen; 2 = Video',
  `interno` int(11) DEFAULT NULL,
  `id_proyecto` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `leyenda` varchar(150) DEFAULT NULL,
  `meta_data` varchar(500) DEFAULT NULL,
  `id_idioma` varchar(5) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_media_proyecto`),
  KEY `fk_t_idiomas_t_media_proyectos_idx` (`id_idioma`),
  KEY `fk_t_media_proyectos_texto_original_idx` (`texto_original`),
  KEY `_idx` (`id_proyecto`),
  CONSTRAINT `` FOREIGN KEY (`id_proyecto`) REFERENCES `m_proyectos` (`id_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_t_idiomas_t_media_proyectos` FOREIGN KEY (`id_idioma`) REFERENCES `s_idiomas` (`id_idioma`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_t_media_proyectos_texto_original` FOREIGN KEY (`texto_original`) REFERENCES `t_media_proyectos` (`id_media_proyecto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_media_proyectos`
--

LOCK TABLES `t_media_proyectos` WRITE;
/*!40000 ALTER TABLE `t_media_proyectos` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_media_proyectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_medias`
--

DROP TABLE IF EXISTS `t_medias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `t_medias` (
  `id_media` int(11) NOT NULL AUTO_INCREMENT,
  `url_media` text,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `externa` tinyint(1) DEFAULT NULL,
  `mime` text,
  `id_proyecto` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_media`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_medias`
--

LOCK TABLES `t_medias` WRITE;
/*!40000 ALTER TABLE `t_medias` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_medias` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-03 14:50:50
