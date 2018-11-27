<?php

namespace Jida\Modulos\Usuario;

use Jida\Core\Modelo;
use Jida\Medios\Debug;
use Jida\Medios\Sesion;
use Jida\Modulos\Usuario\Componentes\Permisos;

class Usuario {

    private $_modelo;
    public $permisos;

    function __construct($id = null) {

        $this->_modelo = new Modelos\Usuario($id);
        $this->_inicializar();

    }

    private function _inicializar() {

        $this->permisos = new Permisos($this->_modelo);

    }

    function iniciarSesion() {

        if (!$this->_modelo->iniciarSesion()) {
            return false;
        }

        Sesion::registrar();
        return $this->_modelo->obtenerPropiedades();
    }

}