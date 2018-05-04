<?php

namespace Jida\Manager\Vista;

use Jida\Helpers as Helpers;
use Jida\Manager\Estructura as Estructura;
use Exception as Excepcion;

class Vista {

    private $_ce = 1000;
    private $_ne = 1;

    private $_directorio;
    private $_nombre;
    private $_data;


    static public $padre;
    private $_DIRECTORIOS = [

        'jida' => 'Jadmin',
        'app'  => ''
    ];

    static public $directorio;


    function __construct ($padre) {

        self::$padre = $padre;

    }


    private function _obtenerDirectorio () {

        $padre = self::$padre;
        $arranque = $padre::$Padre;

        $actual = explode(DS, __DIR__);
        $posicion = array_search('Framework', $actual);

        $directorio = implode("/", array_chunk($actual, $posicion)[0]);

        $directorio .= ($arranque::$ruta !== 'jida') ? "/" . Estructura::DIR_APP : "/" . Estructura::DIR_JIDA;

        if (!!$arranque->modulo) {
            $directorio .= "/Modulos/" . ucfirst($arranque->modulo);
        }

        if ($arranque::$ruta !== 'jida' and $arranque->jadmin) {
            $directorio .= "/" . $this->_DIRECTORIOS['jida'];
        }


        self::$directorio = $directorio . "/Vistas/" . strtolower($arranque::$controlador);

    }


    function obtener () {

        $padre = self::$padre;
        $arranque = $padre::$Padre;
        $controlador = $padre::$controlador;

        if (!self::$directorio) {
            $this->_obtenerDirectorio();
        }

        $vista = (!!$arranque::$metodo) ? $arranque::$metodo : Estructura::NOMBRE_VISTA;
        $vista = (!!$controlador->vista) ? $controlador->vista : $vista;

        $vista = self::$directorio . "/" . $vista;

        if (strpos($vista, '.php') === false) {
            $vista .= ".php";

        };

        Helpers\Debug::imprimir($vista);


        if (!file_exists($vista)) {
            throw new Excepcion('La vista solicitada no existe: ' . $vista, $this->_ce . $this->_ne);
        }


        return $vista;
    }
}