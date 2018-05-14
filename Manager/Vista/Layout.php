<?php

namespace Jida\Manager\Vista;

use function Composer\Autoload\includeFile;
use Jida\Helpers as Helpers;
use Exception as Excepcion;

class Layout {

    private $_DIRECTORIOS = [
        'jida' => 'Layout/',
        'app'  => 'Layout/'
    ];

    private $_ce = '10008';

    public static $padre;
    /**
     * @var _controlador Arranque solicitado por la url
     *
     */
    private $_controlador;

    private $_directorio;

    static public $directorio;
    /**
     * @var _data Objeto Data Vista
     */
    private $_data;

    public function __construct ($padre) {

        self::$padre = $padre;

    }


    public function leer () {

        $padre = self::$padre;
        $arranque = $padre::$Padre;

        $controlador = $arranque::$Controlador;
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

    public function render ($vista) {


        if (!self::$directorio or !$vista) {
            throw  new Excepcion('El parametro $vista es requerido para el metodo render', $this->_ce . '0001');

            return;
        }
        ob_start();

        $contenido = "";

        include_once $vista;
        $contenido = ob_get_clean();

        include self::$directorio;
        $layout = ob_get_clean();

        return $layout;


    }

    /**
     * @deprecated
     */
    public function printHeadTags () {

        $this->imprimirMeta();
    }

    public function imprimirMeta () {


        if (is_object($this->_data)) {
            return Meta::imprimir($this->_data);
        }

        return;
    }
}