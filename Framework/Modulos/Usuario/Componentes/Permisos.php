<?php

namespace Jida\Modulos\Usuario\Componentes;

use Jida\Modulos\Usuario\Modelos\Perfil;
use Jida\Modulos\Usuario\Modelos\Usuario;

class Permisos {

    public $usuario;

    function __construct(Usuario $user = null) {
        //todo: write construct

        if (!$user) {
            $this->_obtener();
        }

    }

    private function _obtener() {
        //todo: obtener perfiles

        $perfil = new Perfil();
        $perfil->obtPerfiles();



    }

    function es($perfiles = []) {
        //todo: write method validation

    }

}