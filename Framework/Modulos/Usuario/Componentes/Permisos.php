<?php

namespace Jida\Modulos\Usuario\Componentes;

use Jida\Medios\Debug;
use Jida\Medios\Sesion;
use Jida\Modelos\UsuarioPerfil;
use Jida\Modulos\Usuario\Modelos\Usuario;

class Permisos {

    private $_perfiles = [];

    function __construct(Usuario $user = null) {

        if (!$user) {
            $this->_obtener();
        }

    }

    private function _obtener() {

        $modelo = new UsuarioPerfil();
        $perfiles = $modelo->obtPerfiles();

        foreach ($perfiles as $id => $datos) {
            $this->_perfiles[$datos['identificador']] = $datos;
        }

    }

    function es($perfiles = []) {

        $band = false;

        $usuario = Sesion::$usuario;

        if ($usuario->id_usuario) {

            Debug::mostrarArray($usuario);

            return true;
        }

        return $band;

    }

}