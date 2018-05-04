<?php


namespace Jida\Manager\Vista;

use Jida\Core\Controller as Controller;
use Jida\Helpers as Helpers;

class Manager {

    use \Jida\Core\ObjetoManager;
    private $_ce = 1006;

    private $_data;
    private $_controller;
    private $_namespace;
    private $_modulo;
    private $_vista;
    private $_layout;

    public $Procesador;
    public $Controlador;

    public $Padre;

    function __construct ($padre, $controlador, $data) {

        $this->Controlador = $controlador;
        $this->Padre = $padre;
        $this->Procesador = $padre->procesador;
        $this->_data = $data;

        $this->_inicializar();

    }


    private function _inicializar () {

        $padre = $this->Padre;
        $this->_controlador = $padre::$controlador;
        $this->_namespace = $padre::$namespace;
        $this->_modulo = $padre->modulo;

        $this->_layout = new Layout($this);
        $vista = $this->vista();


    }

    function excepcion () {

    }

    function renderizar () {

        $salida = $this->_layout
            ->leer()
            ->render($this->vista()->obtener());

        return $salida;


    }


    function vista () {

        if (!$this->_vista) {
            $this->_vista = new Vista($this);
        }

        return $this->_vista;

    }


}