<?php

namespace Jida\Core\Controlador;

use Jida\Helpers\Debug;

Trait Inicio {

    protected $_clase;
    protected $_controlador;
    protected $_metodo;
    protected $_namespace;
    protected $_modulo;

    private function _inicializar () {

        $partes = explode("\\", get_class($this));
        $clase = array_pop($partes);
        $this->_clase = $clase;
        $this->_namespace = implode("\\", $partes);

    }
}