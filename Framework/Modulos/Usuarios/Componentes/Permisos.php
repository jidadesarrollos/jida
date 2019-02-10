<?php

namespace Jida\Modulos\Usuarios\Componentes;

use Jida\Medios\Debug;
use Jida\Modulos\Usuarios\Modelos\Usuario;
use Jida\Modulos\Usuarios\Modelos\UsuarioPerfil;

class Permisos {

    private $_perfiles = [];
    private $_usuario;

    function __construct(Usuario $usuario) {

        $this->_usuario = $usuario;

        if (!$usuario) {
            $this->obtener();
        }

    }

    function obtener() {

        $modelo = new UsuarioPerfil();
        $perfiles = $modelo->obtPerfiles($this->_usuario->id_usuario);

        foreach ($perfiles as $id => $datos) {
            $this->_perfiles[$datos['identificador']] = $datos;
        }

    }

    function es($perfiles) {

        if (is_string($perfiles)) $perfiles = (array)$perfiles;

        foreach ($perfiles as $id => $datos) {
            $arrPerfiles[$datos] = $datos;
        }

        $perfil = [];

        foreach ($this->_perfiles as $identificador => $data) {
            ($data['usuario'] == $this->_usuario->usuario) ? $perfil[$identificador] = $identificador : null;
        }

        return !!array_intersect_key($perfil, $arrPerfiles);

    }

}