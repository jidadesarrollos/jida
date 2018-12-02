<?php

namespace Jida\Modulos\Usuario\Componentes;

use Jida\Modulos\Usuario\Modelos\Perfil;
use Jida\Modulos\Usuario\Modelos\Usuario;

class Permisos {

    public $usuario;
    private $_perfiles = [];

    function __construct(Usuario $user = null) {
        //todo: write construct

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
        //todo: write method validation
        $band = false;


    }

}