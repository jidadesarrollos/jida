-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: cleanmallorca
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
-- Table structure for table `m_estatus_posts`
--

DROP TABLE IF EXISTS `m_estatus_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `m_estatus_posts` (
  `id_estatus_post` int(11) NOT NULL AUTO_INCREMENT,
  `estatus_post` varchar(80) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_estatus_post`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `m_estatus_posts`
--

LOCK TABLES `m_estatus_posts` WRITE;
/*!40000 ALTER TABLE `m_estatus_posts` DISABLE KEYS */;
INSERT INTO `m_estatus_posts` VALUES (1,'Activo',NULL,NULL,NULL,NULL),(2,'Inactivo',NULL,NULL,NULL,NULL),(3,'Borrador',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `m_estatus_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `r_clasificacion_posts`
--

DROP TABLE IF EXISTS `r_clasificacion_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r_clasificacion_posts` (
  `id_post_clasificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) DEFAULT NULL,
  `id_clasificacion_post` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_post_clasificacion`),
  KEY `fk_relacion_Clasifiacion_idx` (`id_clasificacion_post`),
  KEY `fk_t_posts_r_clasificacion_post_idx` (`id_post`),
  CONSTRAINT `fk_relacion_Clasifiacion` FOREIGN KEY (`id_clasificacion_post`) REFERENCES `t_clasificacion_posts` (`id_clasificacion_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_t_posts_r_clasificacion_post` FOREIGN KEY (`id_post`) REFERENCES `t_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r_clasificacion_posts`
--

LOCK TABLES `r_clasificacion_posts` WRITE;
/*!40000 ALTER TABLE `r_clasificacion_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `r_clasificacion_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `r_posts_categorias`
--

DROP TABLE IF EXISTS `r_posts_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `r_posts_categorias` (
  `id_post_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) DEFAULT NULL,
  `id_categoria_post` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_post_categoria`),
  KEY `fk_tpost_idx` (`id_post`),
  KEY `fk_t_categorias_post_idx` (`id_categoria_post`),
  CONSTRAINT `fk_t_categorias_post` FOREIGN KEY (`id_categoria_post`) REFERENCES `t_categorias_posts` (`id_categoria_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tpost` FOREIGN KEY (`id_post`) REFERENCES `t_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `r_posts_categorias`
--

LOCK TABLES `r_posts_categorias` WRITE;
/*!40000 ALTER TABLE `r_posts_categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `r_posts_categorias` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_campos_f`
--

LOCK TABLES `s_campos_f` WRITE;
/*!40000 ALTER TABLE `s_campos_f` DISABLE KEYS */;
INSERT INTO `s_campos_f` VALUES (1,1,'','id_form',NULL,NULL,'',1,'',0,'id_form','','','',NULL,1,NULL),(2,1,'Nombre Formulario','nombre_f',30,30,'\"obligatorio\":{\"mensaje\":\"Debes ingresar un nombre que identifique al formulario\"}',2,NULL,1,'nombre_f',NULL,NULL,NULL,NULL,1,NULL),(3,1,'Query','query_f',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"El campo es obligatorio\"}',3,NULL,2,'query_f',NULL,NULL,NULL,NULL,1,NULL),(4,1,'Clave Primaria','clave_primaria_f',30,30,'',2,'',1,'clave_primaria_f','','','',NULL,1,NULL),(5,2,'a','id_form',NULL,NULL,NULL,1,NULL,2,'id_form',NULL,NULL,NULL,NULL,1,NULL),(6,2,NULL,'id_campo',NULL,3,NULL,1,NULL,1,'id_campo',NULL,NULL,NULL,NULL,1,NULL),(7,2,'Data','data_atributo',100,40,'',3,'',13,'data_atributo','','','data-jidacontrol=\"Ingrese un PlaceHolder\"',NULL,1,NULL),(8,2,'Clase','class',30,50,NULL,2,NULL,17,'class',NULL,NULL,NULL,NULL,1,NULL),(9,2,'ID Propiedad','id_propiedad',30,30,'\"obligatorio\":{\"Mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}',2,NULL,5,'id_propiedad',NULL,NULL,NULL,NULL,1,NULL),(10,2,'Orden','orden',20,20,'',2,'',18,'orden','','','',NULL,1,NULL),(11,2,'Opciones','opciones',NULL,NULL,'',3,'',9,'opciones','','','','',1,NULL),(12,2,'Tipo de Control','control',NULL,NULL,NULL,7,'=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion;9=Telefono',5,'control',NULL,NULL,NULL,NULL,1,NULL),(13,2,'Eventos','eventos',NULL,NULL,'',3,'',10,'eventos','','','','',1,NULL),(14,2,'Size','size',20,20,'',2,'',8,'size','','','','',1,NULL),(15,2,'Maxlength','maxlength',20,20,'',2,'',7,'maxlength','','','','',1,NULL),(16,2,'Name','name',30,30,'\"obligatorio\":{\"Mensaje\":\"El campo debe llevar un nombre\"},\"programa\":{\"Mensaje\":\"El nombre no es valido\"}',2,'',3,'name','','','','',1,NULL),(17,2,'Label','label',30,100,NULL,2,NULL,6,'label',NULL,NULL,NULL,NULL,1,NULL),(18,2,'Placeholder','placeholder',100,30,NULL,2,NULL,14,'placeholder','hola mundo',NULL,NULL,NULL,1,NULL),(19,2,'Title','title',NULL,NULL,'',2,'',15,'title','','','','',1,NULL),(20,2,'Visibilidad','visibilidad',NULL,NULL,NULL,7,'=Seleccione...;1=Normal;2=Readonly;3=Disabled',16,'visibilidad',NULL,NULL,NULL,NULL,1,NULL),(42,1,'Estructura','estructura',50,50,NULL,2,NULL,4,'estructura','Estructura',NULL,NULL,'Estructura de creacion del formulario',1,NULL),(43,2,'Clave','clave_evento',40,40,NULL,2,NULL,11,'clave_evento',NULL,NULL,NULL,NULL,1,NULL),(44,2,'Valor Evento','valor_evento',40,40,NULL,NULL,NULL,12,'valor_evento',NULL,NULL,NULL,NULL,1,NULL),(70,23,'Titulo','titulo',100,50,'\"obligatorio\":{\"mensaje\":\"El titulo de la publicación es obligatorio\"}',2,NULL,1,'titulo','Titulo de la publicación',NULL,NULL,NULL,1,NULL),(71,23,'Relevancia de la Públicación','relevancia',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"Debe indicar la relevancia de la publicación\"} ',6,'2=Normal;1=Importante',4,'relevancia',NULL,NULL,NULL,NULL,1,NULL),(72,23,'Descripción','meta_descripcion',200,NULL,'\"obligatorio\":{\"mensaje\":\"la meta descripción es obligatoria\"}',3,NULL,2,'meta_descripcion',NULL,NULL,NULL,NULL,1,NULL),(73,23,'Fecha de Publicaci&oacute;n','fecha_publicacion',NULL,NULL,'\"fecha\":{\"mensaje\":\"La fecha debe tener formato dd-mm-yyyy o yyyy-mm-dd\"}',10,NULL,5,'fecha_publicacion',NULL,NULL,NULL,NULL,1,NULL),(74,23,'Resumen','resumen',250,NULL,'\"obligatorio\":{\"mensaje\":\"Debe ingresar un resumen de la publicaci&oacute;n\"}',3,NULL,2,'resumen',NULL,NULL,NULL,'Resumen de la publicaci&oacute;n para mostrar en p&aacute;gina principal',1,NULL),(75,24,'Contenido','contenido',NULL,NULL,'\"obligatorio\":{\"mensaje\":\"Debe ingresar el contenido\"}',3,NULL,2,'contenido','Ingrese aqui el contenido del articulo','tiny',NULL,NULL,1,NULL),(76,24,'Titulo','titulo',NULL,NULL,NULL,NULL,NULL,NULL,'titulo','Ingresa el Titulo del Articulo','input-lg',NULL,NULL,1,NULL),(77,24,'Descripcion SEO','meta_descripcion',NULL,NULL,NULL,3,NULL,NULL,'meta_descripcion',NULL,'text-seo',NULL,NULL,1,NULL),(78,24,'Fecha publicacion','fecha_publicacion',NULL,NULL,'\"fecha\":{\"mensaje\":\"Formato de fecha invalido\"}',2,NULL,NULL,'fecha_publicacion',NULL,NULL,NULL,NULL,1,NULL),(79,24,'Estatus:','id_estatus_post',NULL,NULL,NULL,7,'select id_estatus_post,estatus_post from m_estatus_posts',NULL,'id_estatus_post',NULL,NULL,NULL,NULL,1,NULL),(80,24,NULL,'id_media_principal',NULL,NULL,NULL,1,NULL,NULL,'id_media_principal',NULL,NULL,NULL,NULL,1,NULL),(81,24,'Categoria','id_categoria_post',NULL,NULL,NULL,5,'Select id_clasificacion_post,clasificacion_post from t_clasificacion_posts;',NULL,'id_categoria_post',NULL,NULL,NULL,NULL,1,NULL),(82,24,NULL,'id_tag',NULL,NULL,NULL,1,NULL,9,'id_tag',NULL,NULL,NULL,NULL,1,NULL),(83,24,'Resumen','resumen',250,NULL,NULL,3,NULL,NULL,'resumen',NULL,NULL,NULL,NULL,1,NULL),(192,25,'Descripción','descripcion',100,NULL,NULL,3,NULL,NULL,'descripcion','Descripcion',NULL,NULL,NULL,1,NULL),(193,25,'Leyenda','leyenda',150,NULL,NULL,2,NULL,NULL,'leyenda','Leyenda',NULL,NULL,NULL,1,NULL),(194,25,'Alt','alt',45,NULL,'\"obligatorio\":{\"mensaje\":\"Este campo es obligatorio\"}',2,NULL,NULL,'alt','Texto alternativo',NULL,NULL,NULL,1,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_formularios`
--

LOCK TABLES `s_formularios` WRITE;
/*!40000 ALTER TABLE `s_formularios` DISABLE KEYS */;
INSERT INTO `s_formularios` VALUES (1,'formularios','select id_form,nombre_f,query_f,clave_primaria_f,estructura from s_formularios','id_form','Formularios',NULL),(2,'Campos Formulario','select id_campo, id_form, label, name, maxlength, size,\r\neventos, 1 clave_evento, 2 valor_evento, control,  opciones, orden, id_propiedad, placeholder,\r\nclass, data_atributo, title, visibilidad from s_campos_f','id_campo','CamposFormulario','2;3;1;2;1x2;2;1x3;3'),(23,'Registro Posts','select titulo, relevancia, meta_descripcion,resumen, fecha_publicacion from t_posts','id_post','RegistroPosts','1x3;2'),(24,'Edicion Post','\r select titulo,meta_descripcion,resumen,fecha_publicacion, id_estatus_post, id_media_principal, \ncontenido,\r    e.id_clasificacion_post,f.id_clasificacion_post id_tag,relevancia\r    \nfrom t_posts a \r    left join r_clasificacion_posts b on (a.id_post = b.id_post)\r    \nleft join t_clasificacion_posts e on (e.id_clasificacion_post = b.id_clasificacion_post and e.tipo=\'articulo\')\r    \nleft join r_clasificacion_posts d on (a.id_post = d.id_post)\r    \nleft join t_clasificacion_posts f on (f.id_clasificacion_post = d.id_clasificacion_post and f.tipo=\'tag\')\r    ','a.id_post','EdicionPost',NULL),(25,'Gestion Objeto Media','select descripcion, leyenda, alt from s_objetos_media','id_objeto_media','GestionObjetoMedia','1x3');
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
  `identificador` varchar(50) DEFAULT NULL,
  `meta_data` varchar(200) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_menus`
--

LOCK TABLES `s_menus` WRITE;
/*!40000 ALTER TABLE `s_menus` DISABLE KEYS */;
INSERT INTO `s_menus` VALUES (1,'Principal',NULL,NULL,NULL,NULL,NULL,NULL),(2,'Administrador',NULL,NULL,NULL,NULL,NULL,NULL),(4,'esp',NULL,NULL,NULL,NULL,NULL,NULL),(5,'ing',NULL,NULL,NULL,NULL,NULL,NULL),(8,'Clean Mallorca','clean-mallorca',NULL,'2017-03-21 13:16:25','2017-03-21 13:16:25',3,3);
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
  `idioma` varchar(45) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_objeto_media`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_objetos_media`
--

LOCK TABLES `s_objetos_media` WRITE;
/*!40000 ALTER TABLE `s_objetos_media` DISABLE KEYS */;
INSERT INTO `s_objetos_media` VALUES (1,'c8ee254a8fe7b483e3e41c9399a614bc260098.jpeg','/cargas/2017/01/',0,1,NULL,NULL,NULL,'{\"img\":\"c8ee254a8fe7b483e3e41c9399a614bc260098.jpeg\",\"sm\":\"c8ee254a8fe7b483e3e41c9399a614bc260098-sm.jpeg\",\"min\":\"c8ee254a8fe7b483e3e41c9399a614bc260098-min.jpeg\",\"md\":\"c8ee254a8fe7b483e3e41c9399a614bc260098-md.jpeg\",\"lg\":\"c8ee254a8fe7b483e3e41c9399a614bc260098-lg.jpeg\"}',NULL,3,3,'2017-01-12 17:40:42','2017-01-12 17:40:42'),(2,'b52d4f0dbade433a5bc5c9227202a6fa330575.jpeg','/cargas/2017/01/',0,1,NULL,NULL,NULL,'{\"img\":\"b52d4f0dbade433a5bc5c9227202a6fa330575.jpeg\",\"sm\":\"b52d4f0dbade433a5bc5c9227202a6fa330575-sm.jpeg\",\"min\":\"b52d4f0dbade433a5bc5c9227202a6fa330575-min.jpeg\",\"md\":\"b52d4f0dbade433a5bc5c9227202a6fa330575-md.jpeg\",\"lg\":\"b52d4f0dbade433a5bc5c9227202a6fa330575-lg.jpeg\"}',NULL,3,3,'2017-01-12 17:59:39','2017-01-12 17:59:39');
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
  `orden` int(11) DEFAULT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `id_estatus` int(11) DEFAULT NULL,
  `selector_icono` int(11) DEFAULT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_opcion_menu`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `s_opciones_menu_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `s_menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_opciones_menu`
--

LOCK TABLES `s_opciones_menu` WRITE;
/*!40000 ALTER TABLE `s_opciones_menu` DISABLE KEYS */;
INSERT INTO `s_opciones_menu` VALUES (1,1,'/jadmin/forms/','Formularios',0,1,101,'nc-icon-glyph ui-1_edit-76',1,1,NULL,NULL,NULL,NULL,NULL),(2,1,'/jadmin/menus/','Menus',0,0,100,'nc-icon-glyph ui-2_menu-34',1,1,NULL,NULL,NULL,NULL,NULL),(3,1,NULL,'ACL',0,1,100,'nc-icon-glyph ui-1_lock',1,1,NULL,NULL,NULL,NULL,NULL),(4,1,'/jadmin/objetos/','Objetos',3,0,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(5,1,'/jadmin/componentes/','Componentes',3,0,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(9,1,'/jadmin/perfiles/','Perfiles',3,0,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(10,1,'/jadmin/users/cierresesion/','Cerrar Sesión',0,0,200,'nc-icon-glyph ui-1_edit-78',1,1,NULL,NULL,NULL,NULL,NULL),(11,1,'/jadmin/users/','Usuarios',3,0,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(27,1,'/jadmin/forms/jida-forms','Jida',1,0,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL),(28,1,'/jadmin/forms/filter/aplicacion','Aplicaci&oacute;n',1,0,2,'fa-plus-square-o',1,1,NULL,NULL,NULL,NULL,NULL),(29,1,'/algo-distinto/','1',1,0,10,NULL,1,1,NULL,NULL,NULL,NULL,NULL),(33,1,'/jadmin/olds','Forms Viejos',0,0,99,'nc-icon-glyph business_atm',1,1,NULL,NULL,NULL,NULL,NULL),(34,4,'/','Sobre MyDDoc',0,0,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(35,4,'/services','Servicios',0,0,3,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(36,4,'/page-features','Funcionalidades',0,0,2,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(37,4,'/en','',0,0,10,'',1,NULL,NULL,NULL,NULL,NULL,NULL),(38,5,'/en','About MyDDoc',0,0,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(39,5,'/en/page-features','Services',0,0,3,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(40,5,'/en/services','Functions',0,0,2,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(41,5,'/es','',0,0,10,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(42,4,'/prensa','Prensa',0,0,4,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(43,5,'/en/prensa','Press',0,0,4,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL),(44,NULL,NULL,'Inicio',NULL,NULL,1,NULL,1,1,NULL,'2017-03-21 13:43:38','2017-03-21 13:43:38',3,3);
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
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_opciones_menu_perfiles`
--

LOCK TABLES `s_opciones_menu_perfiles` WRITE;
/*!40000 ALTER TABLE `s_opciones_menu_perfiles` DISABLE KEYS */;
INSERT INTO `s_opciones_menu_perfiles` VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,9,1),(7,10,1),(8,11,1),(9,27,1),(10,28,1),(11,29,1),(16,2,1),(17,1,1),(18,10,1),(19,3,1),(22,33,1),(23,34,1),(24,34,1),(25,34,2),(26,34,3),(27,35,1),(28,35,2),(29,35,3),(30,36,1),(31,36,2),(32,36,3),(33,37,1),(34,37,2),(35,37,3),(36,38,1),(37,38,2),(38,38,3),(39,39,1),(40,39,2),(41,39,3),(42,40,1),(43,40,2),(44,40,3),(45,41,1),(46,41,2),(47,41,3),(55,42,1),(56,42,2),(57,42,3),(58,43,1),(59,43,2),(60,43,3);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios`
--

LOCK TABLES `s_usuarios` WRITE;
/*!40000 ALTER TABLE `s_usuarios` DISABLE KEYS */;
INSERT INTO `s_usuarios` VALUES (1,'jadmin','3711be79067177199efb2589054a6894','0000-00-00 00:00:00','0000-00-00 00:00:00',1,1,'2017-03-22 15:35:44','1','Julio','Rodriguez',NULL,NULL,NULL,NULL),(2,'jeanpierre','3711be79067177199efb2589054a6894','0000-00-00 00:00:00','0000-00-00 00:00:00',1,1,'0000-00-00 00:00:00','1',NULL,NULL,NULL,NULL,NULL,NULL),(3,'felix','e10adc3949ba59abbe56e057f20f883e','0000-00-00 00:00:00','0000-00-00 00:00:00',1,1,'2017-03-21 13:06:35','1','Felix','Tovar',NULL,NULL,NULL,NULL),(4,'dayan','e10adc3949ba59abbe56e057f20f883e','0000-00-00 00:00:00','0000-00-00 00:00:00',1,1,'0000-00-00 00:00:00','1','Dayan','Gonzalez',NULL,NULL,NULL,NULL),(5,'admin','e10adc3949ba59abbe56e057f20f883e','0000-00-00 00:00:00','0000-00-00 00:00:00',1,1,'0000-00-00 00:00:00','1','Admin',NULL,NULL,NULL,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_usuarios_perfiles`
--

LOCK TABLES `s_usuarios_perfiles` WRITE;
/*!40000 ALTER TABLE `s_usuarios_perfiles` DISABLE KEYS */;
INSERT INTO `s_usuarios_perfiles` VALUES (11,1,1),(12,1,2),(23,2,1),(24,2,2),(25,3,1),(26,3,2),(27,4,1),(28,4,2),(33,5,1);
/*!40000 ALTER TABLE `s_usuarios_perfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_clasificacion_posts`
--

DROP TABLE IF EXISTS `t_clasificacion_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_clasificacion_posts` (
  `id_clasificacion_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_estatus` int(11) DEFAULT NULL,
  `clasificacion_post` varchar(100) DEFAULT NULL,
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
  `id_lenguaje` int(11) DEFAULT NULL,
  `texto_original` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_clasificacion_post`),
  KEY `fk_s_estatus_idx` (`id_estatus`),
  CONSTRAINT `fk_s_estatus` FOREIGN KEY (`id_estatus`) REFERENCES `s_estatus` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_clasificacion_posts`
--

LOCK TABLES `t_clasificacion_posts` WRITE;
/*!40000 ALTER TABLE `t_clasificacion_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_clasificacion_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_comentarios_posts`
--

DROP TABLE IF EXISTS `t_comentarios_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_comentarios_posts` (
  `id_comentario_post` int(11) NOT NULL AUTO_INCREMENT,
  `comentario_post` text,
  `nombres` varchar(25) DEFAULT NULL,
  `apellidos` varchar(35) DEFAULT NULL,
  `correo` varchar(35) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_estatus_comentarios` int(11) DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  `fecha_Creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_comentario_post`),
  KEY `fk_s_usuarios_idx` (`id_usuario`),
  KEY `fk_t_comentarios_t_post_idx` (`id_post`),
  KEY `fk_m_estatus_comentarios_idx` (`id_estatus_comentarios`),
  CONSTRAINT `fk_m_estatus_comentarios` FOREIGN KEY (`id_estatus_comentarios`) REFERENCES `m_estatus_comentarios` (`id_estatus_comentario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_s_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `s_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_t_comentarios_t_post` FOREIGN KEY (`id_post`) REFERENCES `t_posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_comentarios_posts`
--

LOCK TABLES `t_comentarios_posts` WRITE;
/*!40000 ALTER TABLE `t_comentarios_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_comentarios_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_posts`
--

DROP TABLE IF EXISTS `t_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_posts` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_media_principal` int(11) DEFAULT NULL,
  `titulo` varchar(160) DEFAULT NULL,
  `resumen` varchar(600) DEFAULT NULL,
  `contenido` text,
  `meta_descripcion` varchar(200) DEFAULT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `relevancia` int(11) DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT NULL,
  `numero_visitas` int(11) DEFAULT NULL,
  `id_estatus_post` int(11) DEFAULT NULL,
  `visibilidad` int(11) DEFAULT NULL,
  `nombre_post` varchar(100) DEFAULT NULL,
  `tipo` varchar(25) DEFAULT NULL,
  `data` text,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `id_usuario_creador` int(11) DEFAULT NULL,
  `id_usuario_modificador` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_post`),
  KEY `id_estatus_post_idx` (`id_estatus_post`),
  KEY `fk_t_posts_t_objetos_media_idx` (`id_media_principal`),
  KEY `id_seccion_idx` (`id_seccion`),
  CONSTRAINT `fk_t_posts_t_objetos_media` FOREIGN KEY (`id_media_principal`) REFERENCES `s_objetos_media` (`id_objeto_media`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_estatus_post` FOREIGN KEY (`id_estatus_post`) REFERENCES `m_estatus_posts` (`id_estatus_post`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_seccion` FOREIGN KEY (`id_seccion`) REFERENCES `t_clasificacion_posts` (`id_clasificacion_post`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_posts`
--

LOCK TABLES `t_posts` WRITE;
/*!40000 ALTER TABLE `t_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_posts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-23 16:11:49
