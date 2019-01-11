CREATE TABLE m_categorias (
  id_categoria int not null,
  nombre varchar(250) not null,
  descripcion longtext default null,
  slug text,
  fecha_creacion datetime DEFAULT NULL,
  fecha_modificacion datetime DEFAULT NULL,
  id_usuario_creador int(11) DEFAULT NULL,
  id_usuario_modificador int(11) DEFAULT NULL,
  PRIMARY KEY (id_categoria)
);

CREATE TABLE m_proyectos (
    id_proyectos int not null,
    nombre text not null,
    descripcion longtext default null,
    slug text,
    fecha_creacion datetime DEFAULT NULL,
    fecha_modificacion datetime DEFAULT NULL,
    id_usuario_creador int(11) DEFAULT NULL,
    id_usuario_modificador int(11) DEFAULT NULL,
    id_categoria int not null,
    PRIMARY KEY (id_proyectos)
);

CREATE TABLE t_medias (
    id_media int not null,
    url_media text not null,
    nombre text not null,
    descripcion longtext default null,
    externa bool default null,
    mime text default null,
    fecha_creacion datetime DEFAULT NULL,
    fecha_modificacion datetime DEFAULT NULL,
    id_usuario_creador int(11) DEFAULT NULL,
    id_usuario_modificador int(11) DEFAULT NULL,
    id_proyectos int not null,
    PRIMARY KEY (id_media)
);