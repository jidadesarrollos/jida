<?php

namespace Jida\Modulos\Usuario\Componentes;

use Jida\Medios\Debug;
use Jida\Medios\Sesion;

use Jida\Modulos\Usuario\Modelos\Usuario;
use Jida\Modulos\Usuario\Modelos\UsuarioPerfil;

class Permisos {

    private $_perfiles = [];
    private $_usuario;

    function __construct(Usuario $usuario) {

        $this->_usuario = $usuario;
        if (!$usuario) {
            $this->_obtener();
        }

    }

    function obtener() {

        $modelo = new UsuarioPerfil();
        $perfiles = $modelo->obtPerfiles($this->_usuario->id_usuario);

        foreach ($perfiles as $id => $datos) {
            $this->_perfiles[$datos['identificador']] = $datos;
        }

        Debug::imprimir([$this->_perfiles]);
    }

    function es($perfiles) {

        if (is_string($perfiles)) $perfiles = (array)$perfiles;

        return !!array_intersect($perfiles, $this->_perfiles);

    }

}