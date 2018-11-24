<?php

namespace Jida\Modulos\Usuario;


use Jida\Core\Modelo;
use Jida\Modulos\Usuario\Componentes\Permisos;

class Usuario {

    private $_modelo;
    public $permisos;

    function __construct ($id = null) {

        $this->_modelo = new Modelos\Usuario($id);
        $this->_inicializar();

    }

    private function _inicializar () {

        $this->permisos = new Permisos($this->_modelo);

    }

}