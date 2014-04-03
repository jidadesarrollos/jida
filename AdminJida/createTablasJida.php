<?PHP 
/**
 * Script SQL para creación de tablas del jida
 * 
 * 
 */
$esquema ="";
 

$queryMySql = "
CREATE TABLE if not EXISTS ".$esquema."s_formularios
(
    id_form integer NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_f varchar(80) NOT NULL,
    query_f text NOT NULL,
    clave_primaria_f varchar(45) NOT NULL,
    nombre_identificador varchar(100) NOT NULL
    
)ENGINE=INNODB;";
$queryMySql .="
CREATE TABLE ".$esquema."s_campos_f
(
  id_campo serial,
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

$queryMySql .="
insert into ".$esquema."s_formularios 
values
(null,'formularios','select id_form,nombre_f,query_f from s_formularios ','id_form', 'Formularios'),
(null,'Campos Formulario','select * from s_campos_f ','id_campo' , 'CamposFormulario');";
$queryMySql.="INSERT INTO ".$esquema."s_campos_f
    (id_campo,id_form,label, name, maxlength, size, eventos, control, 
    opciones, orden, id_propiedad, placeholder, class, data_atributo)
VALUES
(null,1,'','id_form',null,null,'',1,'',0,'id_form','','',''),
(null,1,'Nombre Formulario','nombre_f',30,30,'',2,'',1,'nombre_f','','',''),
(null,1,'Query','query_f',null,null,'',3,'',2,'query_f','','',''),
(null,2,'','id_form',null,null,'',1,'',0,'id_form','','',''),
(null,2,'','id_campo',null,null,'',1,'',0,'id_campo','','',''),
(null,2,'Data','data_atributo',100,40,'',3,'',12,'data_atributo','','','data-jidacontrol=\"hola mundo\"'),
(null,2,'Clase','class',30,50,'\"programa\":{\"Mensaje\":\"Las clases CSS solo pueden contener caracteres alfanumericos, guión(-) y underscore(_)\"}',2,'',11,'class','','','')
,
(null,2,'ID Propiedad','id_propiedad',30,30,'\"obligatorio\":{\"Mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}',2,'',3,'id_propiedad aefwefawef','','',''),
(null,2,'Orden','orden',20,20,'',2,'',9,'orden','','',''),
(null,2,'Opciones','opciones',null,null,'',3,'',7,'opciones','','',''),
(null,2,'Control','control',null,null,'',7,'=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion',4,'control','','',''),
(null,2,'Eventos','eventos',null,null,'',3,'',8,'eventos','','',''),
(null,2,'Size','size',20,20,'',2,'',6,'size','','',''),
(null,2,'Maxlength','maxlength',20,20,'',2,'',5,'maxlength','','',''),
(null,2,'Name','name',30,30,'\"obligatorio\":{\"Mensaje\":\"El campo debe llevar un nombre\"},\"programa\":{\"Mensaje\":\"El nombre no es valido\"}',2,'',2,'name','','',''),
(null,2,'Label','label',30,30,'\"obligatorio\":{\"Mensaje\":\"Debe indicar el label del campo\"},\"alfanumerico\":{\"Mensaje\":\"el Label solo debe poseer caracteres\"}',2,'',1,'label','','',''),
(null,2,'Clave primaria','clave_primaria_f',null,null,'',2,'',3,'clave_primaria_f','ingresa tu clave primaria','',''),
(null,2,'Placeholder','placeholder',100,30,'\"alfanumerico\":{\"Mensaje\":\"El placeholder solo puede contener letras y numeros\"}',2,'',10,'placeholder','hola mundo','',''),
(null,2,'Title','title',null,null,'',2,'',10,'title','','',''),
(null,2,'Visibilidad','visibilidad',null,null,'',7,'=Seleccione...;1=Normal;2=Readonly;3=Disabled',10,'visibilidad','','','')

;\n";

$queryMySql="
CREATE TABLE ".$esquema."s_perfiles
  id_perfil serial,
  nombre_perfil varchar(50)
  fecha_creado  datetime,
 )ENGINE=INNODB;\n";

$queryMySql = "
CREATE TABLE ".$esquema."s_usuarios
(
    id_usuario serial,
    nombre_usuario varchar(100) NOT NULL,
    clave_usuario varchar(50) NOT NULL,
    fecha_creado datetime,
    fecha_modificado datetime,
    activo boolean
 )ENGINE = INNODB;\n   
";
$queryMySql = "
CREATE TABLE  ".$esquema."s_usuarios_perfiles
(
    id_usuario_perfil serial,
    id_usuario int NOT NULL,
    id_perfil int NOT NULL,
    FOREIGN KEY (id_usuario)
      REFERENCES ".$esquema."s_usuarios (id_usuario)
      ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_perfil)
      REFERENCES ".$esquema."s_perfiles (id_perfil)
      ON UPDATE CASCADE ON DELETE CASCADE,
)ENGINE=INNODB;\n
";

$queryMySql = "
insert into s_perfiles VALUES
(null,'Jida Administrador',now()),
(null,'Administrador',now()),
(null,'Usuario',now()));
INSERT INTO s_usuarios 
(id_usuario,nombre_usuario,clave_usuario,fecha_creado,activo)VALUES 
(null,'jadmin','jadmin',now(),1);
INSERT INTO s_usuarios_perfiles 
values
(null,1,1)
";


$queryPostgres = "
CREATE TABLE ".$esquema."s_formularios
(
  id_form serial,
  nombre_f character varying(80) NOT NULL,
  query_f text NOT NULL,
  clave_primaria_f character varying(45) NOT NULL,
  nombre_identificador character varying(100) NOT NULL,
  CONSTRAINT pk_id_form PRIMARY KEY (id_form)
);
CREATE TABLE ".$esquema."s_campos_f
(
  id_campo serial,
  id_form integer,
  label character varying(80) DEFAULT NULL::character varying,
  name character varying(60) NOT NULL,
  maxlength integer,
  size integer,
  eventos text,
  control integer,
  opciones text,
  orden integer,
  id_propiedad character varying(50) DEFAULT NULL::character varying,
  placeholder character varying(50),
  class character varying(100),
  data_atributo character varying(500),
  title character varying(500),
  visibilidad int DEFAULT 1::int,
  CONSTRAINT fk_id_campo PRIMARY KEY (id_campo),
  CONSTRAINT fk_formulario_campo FOREIGN KEY (id_form)
      REFERENCES ".$esquema."s_formularios (id_form) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

insert into ".$esquema."s_formularios (nombre_f,query_f,clave_primaria_f,nombre_identificador)
values
('formularios','select id_form,nombre_f,query_f from s_formularios ','id_form','Formularios'),
('Campos Formulario','select * from s_campos_f ','id_campo','CamposFormulario')
;
INSERT INTO s_campos_f
    (id_form,label,name, maxlength, size, eventos, control, opciones, orden, id_propiedad, 
    placeholder, class, data_atributo)

VALUES
(1,'','id_form',null,null,'',1,'',0,'id_form','','',''),
(1,'Nombre Formulario','nombre_f',30,30,'',2,'',1,'nombre_f','','',''),
(1,'Query','query_f',null,null,'',3,'',2,'query_f','','',''),
(2,'','id_form',null,null,'',1,'',0,'id_form','','',''),
(2,'','id_campo',null,null,'',1,'',0,'id_campo','','',''),
(2,'Data','data_atributo',100,40,'',3,'',12,'data_atributo','','','data-jidacontrol=\"hola mundo\"'),
(2,'Clase','class',30,50,'\"programa\":{\"Mensaje\":\"Las clases CSS solo pueden contener caracteres alfanumericos, guión(-) y underscore(_)\"}',2,'',11,'class','','','')
,
(2,'ID Propiedad','id_propiedad',30,30,'\"obligatorio\":{\"Mensaje\":\"La propiedad Id del campo es obligatoria\"},\"programa\":{\"Mensaje\":\"La propiedad no puede contener espacios ni caracteres especiales\"}',2,'',3,'id_propiedad aefwefawef','','',''),
(2,'Orden','orden',20,20,'',2,'',9,'orden','','',''),
(2,'Opciones','opciones',null,null,'',3,'',7,'opciones','','',''),
(2,'Control','control',null,null,'',7,'=Seleccione;1=Hidden;2=Text;3=Textarea;4=Password;5=Checkbox;6=Radio;7=Seleccion;8=Identificacion',4,'control','','',''),
(2,'Eventos','eventos',null,null,'',3,'',8,'eventos','','',''),
(2,'Size','size',20,20,'',2,'',6,'size','','',''),
(2,'Maxlength','maxlength',20,20,'',2,'',5,'maxlength','','',''),
(2,'Name','name',30,30,'\"obligatorio\":{\"Mensaje\":\"El campo debe llevar un nombre\"},\"programa\":{\"Mensaje\":\"El nombre no es valido\"}',2,'',2,'name','','',''),
(2,'Label','label',30,30,'\"obligatorio\":{\"Mensaje\":\"Debe indicar el label del campo\"},\"alfanumerico\":{\"Mensaje\":\"el Label solo debe poseer caracteres\"}',2,'',1,'label','','',''),
(2,'Clave primaria','clave_primaria_f',null,null,'',2,'',3,'clave_primaria_f','ingresa tu clave primaria','',''),
(2,'Placeholder','placeholder',100,30,'\"alfanumerico\":{\"Mensaje\":\"El placeholder solo puede contener letras y numeros\"}',2,'',10,'placeholder','hola mundo','','')
(2,'Title','title',null,null,'',2,'',10,'title','','',''),
(2,'Visibilidad','visibilidad',null,null,'',7,'=Seleccione...;1=Normal;2=Readonly;3=Disabled',10,'visibilidad','','','')
;
";
?>