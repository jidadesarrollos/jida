create or replace View v_acceso_objetos as 
select 
id_objeto_perfil,
a.id_perfil,
c.clave_perfil,
nombre_perfil,
a.id_objeto,objeto
from s_objetos_perfiles a
join s_objetos b on (b.id_objeto = a.id_objeto)
join s_perfiles c on (c.id_perfil = a.id_perfil);

--1 Corintios 10:13