<?php

namespace Jida\Modulos\Usuario\Modelos;

use Jida\Core\Modelo;

class UsuarioPerfil extends Modelo {

    public $id_perfil;
    public $id_usuario;
    public $id_usuario_perfil;

    protected $tablaBD = "s_usuarios_perfiles";
    protected $pk      = "id_usuario_perfil";

    public function obtPerfiles($idUsuario) {

        $this->consulta
        (['id_usuario_perfil',
            'id_usuario',
            'id_perfil']);
        $this->join(
            's_perfiles',
            ['identificador'],
            ['clave' => 'id_perfil', 'clave_relacion' => 'id_perfil']);
        $this->join(
            's_usuarios',
            ['nombre_usuario', 'nombres', 'apellidos'],
            ['clave' => 'id_usuario', 'clave_relacion' => 'id_usuario']);
        $this->filtro(['id_usuario' => $idUsuario]);

        $perfiles = $this->obt();

        return $perfiles;

    }

}