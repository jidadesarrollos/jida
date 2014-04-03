create or replace view v_acceso_metodos 
AS
select id_metodo_perfil,
a.id_metodo,
b.nombre_metodo,
a.id_perfil,
c.clave_perfil,
d.objeto
from s_metodos_perfiles a
join s_metodos b on (a.id_metodo = b.id_metodo)
join s_perfiles c on (a.id_perfil = c.id_perfil)
join s_objetos d on (b.id_objeto = d.id_objeto)