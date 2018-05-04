<?php

namespace Jida\Manager\Vista;

use Jida\Helpers as Helpers;

class Layout {

    private $_DIRECTORIOS = [

        'jida' => 'Layout/',
        'app'  => 'Layout/'
    ];

    private $_padre;
    /**
     * @var _controlador Arranque solicitado por la url
     *
     */
    private $_controlador;

    private $_directorio;

    static public $directorio;

    public function __construct ($padre) {

        $this->_padre = $padre;
        $this->_controlador;

    }


    public function leer () {

        $arranque = $this->_padre->Padre;
        $controlador = $arranque::$Controlador;
        Helpers\Debug::imprimir(get_class($controlador), get_class($this->_padre));

        $directorio = $this->_DIRECTORIOS['app'];

        if ($arranque->jadmin) {

            $this->_directorio = "./Framework/";
            $directorio = $this->_DIRECTORIOS['jida'];

        }

        self::$directorio = $this->_directorio . $directorio . $controlador->layout;

        return $this;

    }

    public function imprimirLibrerias () {

    }

    public function render () {

        ob_start();

        $contenido = "";

        include self::$directorio;
        $contenido = ob_get_clean();



    }
}