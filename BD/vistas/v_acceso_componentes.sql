Create or replace view v_acceso_componentes
AS
select 
id_objeto_perfil,
a.id_perfil,
clave_perfil,
a.id_componente,
componente
from s_componentes_perfiles a
join s_perfiles b on (a.id_perfil = b.id_perfil)
join s_componentes c on (c.id_componente = a.id_componente)