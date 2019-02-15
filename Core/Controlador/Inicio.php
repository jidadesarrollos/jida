<?php

namespace Jida\Core\Controlador;

use Jida\Manager\Vista\Data;

Trait Inicio {

    protected $_clase;
    protected $_controlador;
    protected $_metodo;
    protected $_namespace;
    protected $_modulo;

    /**
     * @var object $data Objeto Data para pasar la informacion a las vistas
     * @see Data
     */
    private $_data;

    private function _inicializar () {

        $partes = explode("\\", get_class($this));
        $clase = array_pop($partes);
        $this->_clase = $clase;
        $this->_namespace = implode("\\", $partes);
        $this->_data = Data::inicializar(null);

    }


}