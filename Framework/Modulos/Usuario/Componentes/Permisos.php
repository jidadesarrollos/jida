<?php

namespace Jida\Modulos\Usuario\Componentes;

use Jida\Modulos\Usuario\Modelos\Usuario;
use Jida\Modulos\Usuario\Modelos\UsuarioPerfil;

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

        return !!array_intersect_key($arrPerfiles, $this->_perfiles);

    }

}