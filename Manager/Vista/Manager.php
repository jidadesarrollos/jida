<?php


namespace Jida\Manager\Vista;

use Jida\Core\Controller as Controller;
use Jida\Helpers as Helpers;

class Manager {

    use \Jida\Core\ObjetoManager;

    private $_ce = 10006;
    private $_data;

    private $_namespace;
    private $_modulo;
    private $_layout;

    public $Procesador;


    static public $controlador;
    static public $Padre;
    static public $vista;

    function __construct ($padre, $controlador, $data) {

        self::$controlador = $controlador;

        $this->Procesador = $padre->procesador;
        $this->_data = $data;

        self::$Padre = $padre;

        $this->_inicializar();

    }


    private function _inicializar () {

        $padre = self::$Padre;

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

        if (!self::$vista) {
            self::$vista = new Vista($this);
        }

        return self::$vista;

    }


}