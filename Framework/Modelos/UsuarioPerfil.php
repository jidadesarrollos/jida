<?php

namespace Jida\Modelos;

use Jida\BD\DataModel;

class UsuarioPerfil extends DataModel {

    var $id_usuario_perfil;
    var $id_usuario;
    var $id_perfil;

    protected $tablaBD = "s_usuarios_perfiles";
    protected $pk = "id_usuario_perfil";

    function obtPerfiles ($idUsuario) {

        $this->consulta(['id_usuario_perfil', 'id_usuario', 'id_perfil']);
        $this->join('s_perfiles',
                    ['clave_perfil'],
                    ['clave' => 'id_perfil', 'clave_relacion' => 'id_perfil']);
        $this->join('s_usuarios',
                    ['nombre_usuario', 'nombres', 'apellidos'],
                    ['clave' => 'id_usuario', 'clave_relacion' => 'id_usuario']);
        $this->filtro(['id_usuario' => $idUsuario]);
        $resultado = $this->obt();

        return $resultado;

    }

}