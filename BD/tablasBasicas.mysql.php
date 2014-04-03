<?PHP 
/**
 * Estructura de tablas basicas del Framework para
 * Base de Datos MySQL
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category Base de Datos
 */
 

$queryBD = "
CREATE TABLE if not EXISTS ".$esquema."s_formularios
(
    id_form integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_f varchar(80) NOT NULL,
    query_f text NOT NULL,
    clave_primaria_f varchar(45),
    nombre_identificador varchar(100) NOT NULL
    
)ENGINE=INNODB;";
$queryBD .="
CREATE TABLE ".$esquema."s_campos_f
(
  id_campo integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_form integer NOT NULL,
  label varchar(80) default null,
  name varchar(60) NOT NULL,
  maxlength integer,
  size integer,
  eventos text,
  control integer,
  opciones text,
  orden integer,
  id_propiedad varchar(50) default null,
  placeholder varchar(50),
  class varchar(100),
  data_atributo varchar(500),
  title varchar(500),
  visibilidad int default 1,
  FOREIGN KEY (id_form)
      REFERENCES ".$esquema."s_formularios (id_form)
      ON UPDATE NO ACTION ON DELETE NO ACTION
  
)ENGINE=INNODB;";

$queryBD.="
CREATE TABLE ".$esquema."s_perfiles
(
  id_perfil integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre_perfil varchar(50),
  fecha_creado  datetime
 )ENGINE=INNODB;\n";

 
$tablaUsuarios= "
CREATE TABLE ".$esquema."s_usuarios
(
    id_usuario integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario varchar(100) NOT NULL,
    clave_usuario varchar(50) NOT NULL,
    fecha_creado datetime,
    fecha_modificado datetime,
    activo boolean
 )ENGINE = INNODB;\n   
";

$queryBD.=$tablaUsuarios;
$queryBD.= "
CREATE TABLE  ".$esquema."s_usuarios_perfiles
(
    id_usuario_perfil integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario integer NOT NULL,
    id_perfil integer NOT NULL,
    FOREIGN KEY (id_usuario)
      REFERENCES ".$esquema."s_usuarios (id_usuario)
      ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_perfil)
      REFERENCES ".$esquema."s_perfiles (id_perfil)
      ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=INNODB;
CREATE TABLE s_menus
(
id_menu integer NOT NULL  AUTO_INCREMENT PRIMARY KEY,
nombre_menu varchar(30) NOT null
);

CREATE TABLE s_opciones_menu
(
id_opcion integer NOT NULL  AUTO_INCREMENT PRIMARY KEY,
id_menu integer,
url_opcion varchar(100),
nombre_opcion varchar(100) not null,
padre integer,
hijo boolean,
fecha_creado datetime,
fecha_modificado datetime,
FOREIGN KEY (id_menu)
REFERENCES s_menus (id_menu)
ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE s_opciones_menu_perfiles
(
id_opcion_menu_perfil integer not null AUTO_INCREMENT PRIMARY KEY,
id_opcion integer,
id_perfil integer,
FOREIGN KEY (id_opcion)
REFERENCES s_opciones_menu (id_opcion)
ON UPDATE CASCADE ON DELETE CASCADE,
FOREIGN KEY (id_perfil)
REFERENCES s_perfiles (id_perfil)
ON UPDATE CASCADE ON DELETE CASCADE
);
";
$s_componentes = "Create table s_componentes(
id_componente int primary key auto_increment,
componente varchar(100) not null
)ENGINE=INNODB;
";

$s_objetos = "create table s_objetos(
id_objeto int primary key auto_increment,
id_componente int,
objeto varchar(100),
Foreign key (id_componente)
references s_componentes (id_componente)
on update no action on delete no action
)ENGINE=INNODB;";

$s_acl_objetos="create table s_metodos(
id_metodo int primary key auto_increment,
id_objeto int,
nombre_metodo varchar(150),
Foreign key (id_objeto)
REFERENCES s_objetos (id_objeto)
on update no action on delete no action 
)ENGINE=INNODB;
";
$s_metodos_perfil = "create table s_metodos_perfil(
id_objeto_perfil int primary key auto_increment,
id_metodo int,
id_perfil int,
FOREIGN key (id_metodo)
REFERENCES s_metodos (id_metodo)
on update no action on delete no action,
foreign key (id_perfil)
references s_perfiles (id_perfil)
on update no action on delete no action
)ENGINE=INNODB;
";
$insertComponentes = "
insert into ".$esquema."s_componentes values (null,'Principal');
";

$queryBD .=$s_componentes . $s_objetos . $s_acl_objetos .$s_metodos_perfil . $insertOpcionesMenu ."

insert into ".$esquema."s_formularios 
values
(null,'formularios','select id_form,nombre_f,query_f,clave_primaria_f from s_formularios ','id_form', 'Formularios'),
(null,'Campos Formulario','select * from s_campos_f ','id_campo' , 'CamposFormulario'),
(null,'Login','select nombre_usuario,clave_usuario from s_usuarios',NULL,'Login'),
(4,'Procesar menus','select * from s_menus','id_menu','ProcesarMenus'),
(5,'Procesar opcion menu','select id_opcion,id_menu,url_opcion,nombre_opcion,padre from s_opciones_menu','id_opcion','ProcesarOpcionMenu');";
$insertCampos= "INSERT INTO ".$esquema."s_campos_f
    (id_campo,id_form,label, name, maxlength, size, eventos, control, 
    opciones, orden, id_propiedad, placeholder, class, data_atributo,title,visibilidad)
VALUES
(null,1,'','id_form',null,null,'',1,'',0,'id_form','','','',null,1),
(null,1,'Nombre Formulario','nombre_f',30,30,'',2,'',1,'nombre_f','','','',null,1),
(null,1,'Query','query_f',null,null,'',3,'',2,'query_f','','','',null,1),
(null,1,'Clave Primaria','clave_primaria_f',30,30,'',2,'',1,'clave_primaria_f','','','',null,1),
(null,2,'','id_form',null,null,'',1,'',0,'id_form','','','',null,1),
(null,2,'','id_campo',null,null,'',1,'',0,'id_campo','','','',null,1),
(null,2,'Data','data_atributo',100,40,'',3,'',12,'data_atributo','','','data-jidacontrol=\"Ingrese un PlaceHolder\"',null,1),
(null,2,'Clase','class',30,50,'\"programa\":{\"Mensaje\":\"Las clases CSS solo pueden contener caracteres alfanumericos, guión(-) y underscore(_)\"}',2,'',11,'class','','','',null,1)
,
(null,2,'ID Propiedad','id_propiedad',30,30,'\"obligatorio\":{\"Mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}',2,'',3,'id_propiedad aefwefawef','','','',null,1),
(null,2,'Orden','orden',20,20,'',2,'',9,'orden','','','',null,1),
(null,2,'Opciones','opciones',null,null,'',3,'',7,'opciones','','','','',1),
(null,2,'Control','control',null,null,'',7,'=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion',4,'control','','','','',1),
(null,2,'Eventos','eventos',null,null,'',3,'',8,'eventos','','','','',1),
(null,2,'Size','size',20,20,'',2,'',6,'size','','','','',1),
(null,2,'Maxlength','maxlength',20,20,'',2,'',5,'maxlength','','','','',1),
(null,2,'Name','name',30,30,'\"obligatorio\":{\"Mensaje\":\"El campo debe llevar un nombre\"},\"programa\":{\"Mensaje\":\"El nombre no es valido\"}',2,'',2,'name','','','','',1),
(null,2,'Label','label',30,30,'\"alfanumerico\":{\"Mensaje\":\"el Label solo debe poseer caracteres\"}',2,'',1,'label','','','','',1),
(null,2,'Placeholder','placeholder',100,30,'\"alfanumerico\":{\"Mensaje\":\"El placeholder solo puede contener letras y numeros\"}',2,'',10,'placeholder','hola mundo','','','',1),
(null,2,'Title','title',null,null,'',2,'',10,'title','','','','',1),
(null,2,'Visibilidad','visibilidad',null,null,'',7,'=Seleccione...;1=Normal;2=Readonly;3=Disabled',10,'visibilidad',NULL,NULL,NULL,NULL,1),
(null,3,'Nombre Usuario','nombre_usuario',30,NULL,'\"obligatorio\":{\"Mensaje\":\"Debe ingresar su nombre de Usuario\",},\"alfanumerico\":{\"Mensaje\":\"El nombre de usuario solo puede poseer caracteres alfanumericos\"}',2,NULL,1,'nombre_usuario','Nombre de usuario',null,null,null,1),
(null,3,'Clave','clave_usuario',30,NULL,'\"obligatorio\":{\"Mensaje\":\"Debe ingresar su clave\",}',4,NULL,2,'clave_usuario',NULL,NULL,NULL,NULL,1),
(null,4,NULL,'id_menu',NULL,NULL,NULL,1,NULL,NULL,'id_menu',NULL,NULL,NULL,NULL,1),
(null,4,'Nombre Menu','nombre_menu',100,50,'\"obligatorio\":{\"mensaje\",\"Debe ingresar un nombre identificador del menu\"}',2,NULL,1,'nombre_menu',NULL,NULL,NULL,NULL,1),
(null,5,NULL,'id_opcion',NULL,NULL,NULL,1,NULL,NULL,'id_opcion',NULL,NULL,NULL,NULL,1),
(null,5,NULL,'id_menu',NULL,NULL,NULL,1,NULL,NULL,'id_menu',NULL,NULL,NULL,NULL,1),
(null,5,'URL','url_opcion',100,50,'\"programa\":{\"mensaje\",\"formato de url invalido \"}',2,NULL,NULL,'url_opcion',NULL,NULL,NULL,NULL,1),
(null,5,'Nombre de opcion','nombre_opcion',100,50,'\"obligatorio\":{\"mensaje\":\"El nombre de la opcion es obligatorio\"}',2,NULL,2,'nombre_opcion','Nombre a verse en el menu',NULL,NULL,'nombre que se vera en el menu',1),
(null,5,'Padre','padre',NULL,NULL,NULL,7,'0=No Aplica;select id_opcion,nombre_opcion from s_opciones_menu',NULL,'padre',NULL,NULL,NULL,NULL,1);
";
$queryBD.=$insertCampos;

$insertMenus=
"
INSERT INTO s_menus (id_menu, nombre_menu) VALUES (1,'Principal');";
$insertOpcionesMenu="
INSERT INTO s_opciones_menu 
(id_opcion, id_menu, url_opcion, nombre_opcion, padre, hijo, fecha_creado, fecha_modificado) 
VALUES 
(1,1,'/jadmin/','Administrador',0,0,current_timestamp,NULL),
(2,1,'/jadmin/menus/','Menus',0,0,current_timestamp,NULL),
(3,1,null,'ACL',0,1,current_timestamp,NULL),
(NULL,1,'/jadmin/objetos/','Objetos',3,0,current_timestamp,NULL),
(NULL,1,'/jadmin/componentes/','Componentes',3,0,current_timestamp,NULL)
;";

$queryBD.=$insertMenus.$insertOpcionesMenu;



$iPerfiles= "
insert into s_perfiles VALUES
(null,'Jida Administrador',now()),
(null,'Administrador',now()),
(null,'Usuario',now());
INSERT INTO s_usuarios 
(id_usuario,nombre_usuario,clave_usuario,fecha_creado,activo)VALUES 
(null,'jadmin','jadmin',now(),1);
INSERT INTO s_usuarios_perfiles 
values
(null,1,1);
";
$s_objetos_perfiles = "Create table s_objetos_perfiles(
id_objeto_perfil int primary key auto_increment,
id_perfil int not null,
id_objeto int not null,
foreign KEY (id_perfil)
    REFERENCES s_perfiles (id_perfil)
    ON UPDATE NO ACTION ON DELETE NO ACTION,
foreign KEY (id_objeto)
    REFERENCES s_objetos(id_objeto)
    ON UPDATE NO ACTION ON DELETE NO ACTION
)ENGINE=INNODB;";
$queryBD.=$iPerfiles . $s_objetos_perfiles;
#echo $queryBD;exit;


?>
