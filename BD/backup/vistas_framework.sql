CREATE  or Replace
    
VIEW `vj_acceso_componentes` AS
    select 
        `a`.`id_componente_perfil` AS `id_componente_perfil`,
        `a`.`id_perfil` AS `id_perfil`,
        `b`.`clave_perfil` AS `clave_perfil`,
        `a`.`id_componente` AS `id_componente`,
        `c`.`componente` AS `componente`
    from
        ((`s_componentes_perfiles` `a`
        join `s_perfiles` `b` ON ((`a`.`id_perfil` = `b`.`id_perfil`)))
        join `s_componentes` `c` ON ((`c`.`id_componente` = `a`.`id_componente`)));
		
		
		CREATE or REPLACE view vj_acceso_metodos as
select a.id_metodo,
a.id_objeto,
d.objeto,
metodo,
loggin,
b.id_perfil,
c.clave_perfil,
c.perfil,
e.id_componente,
e.componente
from s_metodos a
left join s_metodos_perfiles b on (a.id_metodo=b.id_metodo) 
left join s_perfiles c on (c.id_perfil=b.id_perfil)
join s_objetos d on (a.id_objeto=d.id_objeto)
join s_componentes e on (d.id_componente=e.id_componente)
where b.id_perfil is not null  or loggin=0;

CREATE or REPLACE VIEW vj_acceso_objetos AS
    select 
        a.id_objeto_perfil AS id_objeto_perfil,
        a.id_perfil AS id_perfil,
        c.clave_perfil AS clave_perfil,
        c.perfil AS nombre_perfil,
        a.id_objeto AS id_objeto,
        b.objeto AS objeto,
        b.id_componente AS id_componente
    from
        ((s_objetos_perfiles a
        join s_objetos b ON ((b.id_objeto = a.id_objeto)))
        join s_perfiles c ON ((c.id_perfil = a.id_perfil)));
		
		CREATE or REPLACE VIEW vj_perfiles_usuario AS
select 
	a.id_usuario_perfil AS id_usuario_perfil,
	a.id_perfil AS id_perfil,
	a.id_usuario AS id_usuario,
	c.nombre_usuario,
	c.nombres,
	c.apellidos,
	b.clave_perfil AS clave_perfil
from
	s_usuarios_perfiles a
	join s_perfiles b ON (a.id_perfil = b.id_perfil)
	join s_usuarios c on (a.id_usuario = c.id_usuario);

	
	CREATE or REPLACE VIEW vj_perfiles_usuarios AS
select 
	a.id_usuario_perfil AS id_usuario_perfil,
	a.id_perfil AS id_perfil,
	a.id_usuario AS id_usuario,
	c.nombre_usuario,
	c.nombres,
	c.apellidos,
	b.clave_perfil AS clave_perfil
from
	s_usuarios_perfiles a
	join s_perfiles b ON (a.id_perfil = b.id_perfil)
	join s_usuarios c on (a.id_usuario = c.id_usuario);
