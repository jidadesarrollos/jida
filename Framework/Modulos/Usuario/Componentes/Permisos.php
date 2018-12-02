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

    }

    function es($perfiles = []) {

        $band = false;

        $usuario = Sesion::$usuario;

        if ($usuario->obtener('id_usuario')) {

            Debug::mostrarArray($usuario);

            return true;
        }

        return $band;

    }

}